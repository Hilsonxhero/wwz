<?php

namespace Modules\Cart\Services;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Collection;
use Modules\Cart\Enums\CartStatus;
use Modules\Cart\Contracts\Buyable;
use Modules\Cart\Entities\CartItem;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\Product\Entities\ProductVariant;
use Modules\Cart\Contracts\InstanceIdentifier;
use Modules\Cart\Entities\Cart as EntitiesCart;
use Modules\Product\Repository\ProductRepository;
use Modules\Cart\Exceptions\UnknownModelException;
use Modules\Cart\Repository\CartRepositoryInterface;
use Modules\Cart\Transformers\App\CartItemsResource;
use Modules\User\Repository\UserRepositoryInterface;
use Modules\Product\Transformers\Cart\ProductResource;
use Modules\Cart\Repository\CartItemRepositoryInterface;
use Modules\Product\Transformers\Cart\ProductVariantResource;
use Modules\Product\Repository\ProductVariantRepositoryInterface;

class Cart
{
    use Macroable;

    const DEFAULT_INSTANCE = 'default';

    /**
     * Instance of the session manager.
     *
     * @var \Illuminate\Redis\RedisManager
     */

    private $storage;

    private $shipment_cost = 0;

    private $voucher_discount = 0;

    private $shipment_discount = 0;

    /**
     * provide cart token cookie.
     */

    private $cart_token;

    /**
     * return user .
     *
     *  @var object
     */

    private $user;

    /**
     * check user authenticated .
     *
     *  @var boolean
     */

    private $authenticated;


    /**
     * Instance of the event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    private $events;

    /**
     * Holds the current cart instance.
     *
     * @var string
     */
    private $instance;

    /**
     * Holds the creation date of the cart.
     *
     * @var mixed
     */
    private $createdAt;

    /**
     * Holds the update date of the cart.
     *
     * @var mixed
     */
    private $updatedAt;

    /**
     * Defines the discount percentage.
     *
     * @var float
     */
    private $discount = 0;

    /**
     * Defines the tax rate.
     *
     * @var float
     */
    private $taxRate = 0;



    /**
     * Cart constructor.
     *
     * @param \Illuminate\Redis\RedisManager $storage
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     */

    public function __construct(RedisManager $storage, Dispatcher $events)
    {
        $this->cart_token  = request()->cookie('cart_token');
        $this->storage = $storage;
        $this->authenticated = auth()->check();
        $this->user = auth()->user();
        $this->events = $events;
        $this->taxRate = config('cart.tax');
        $this->instance(self::DEFAULT_INSTANCE);
    }

    /**
     * Set the current cart instance.
     *
     * @param string|null $instance
     *
     * @return \Modules\Cart\Contracts\Cart
     */
    public function instance($instance = null)
    {

        $cart_token = $this->cart_token;

        if (!$cart_token) {
            $token = Str::random(12);
            $cart_token =  Cookie::queue(
                'cart_token',
                $token,
                999999,
                null,
                null,
                false,
                true,
                false,
                'Strict'
            );
        } else {
            $token = $cart_token;
        }



        $instance = $token ?: self::DEFAULT_INSTANCE;

        if ($instance instanceof InstanceIdentifier) {
            $this->discount = $instance->getInstanceGlobalDiscount();
            $instance = $instance->getInstanceIdentifier();
        }

        $this->instance = 'cart.' . $instance;

        return $this;
    }

    /**
     * Get the current cart instance.
     *
     * @return string
     */
    public function currentInstance()
    {
        return str_replace('cart.', '', $this->instance);
    }

    /**
     * Add an item to the cart.
     *
     * @param mixed     $id
     * @param mixed     $name
     * @param int|float $quantity
     * @param float     $price
     * @param float     $weight
     * @param array     $options
     *
     * @return \Modules\Cart\Services\CartItem
     */
    public function add($id, $product = null, $variant = null, $discount = 0, $price = null, $weight = 0, $quantity = null, array $options = [])
    {

        if ($this->isMulti($id)) {
            return array_map(function ($item) {
                return $this->add($item);
            }, $id);
        }

        $cartItem = (object) array(
            "rowId" => md5($id),
            "id" => $id,
            "product" => $product,
            "variant" => $variant,
            "quantity" => $quantity,
            "price" => $price,
            "weight" => $weight,
            "options" => $options,
        );


        // return $cartItem;

        return $this->addCartItem($cartItem, $discount);
    }

    /**
     * Add an item to the cart.
     *
     * @param \Modules\Cart\Services\CartItem $item          Item to add to the Cart
     * @param bool                              $keepDiscount  Keep the discount rate of the Item
     * @param bool                              $keepTax       Keep the Tax rate of the Item
     * @param bool                              $dispatchEvent
     *
     * @return \Modules\Cart\Services\CartItem The CartItem
     */
    public function addCartItem($item, $discount, $keepDiscount = false, $keepTax = false, $dispatchEvent = true)
    {
        if (!auth()->check()) {
            $content = $this->getContent();
            if ($content->has($item->id)) {
                $item->quantity += $content->get($item->id)->quantity;
            }
            $content->put($item->id, $item);
            $this->storage->set($this->instance, serialize($content));
        } else {
            $user = auth()->user();

            if (is_null($user->available_cart)) {
                $cart = resolve(CartRepositoryInterface::class)->firstOrCreate([
                    'user_id' => $user->id,
                    'status' => CartStatus::Available->value
                ], [
                    'user_id' => $user->id,
                    'identifier' => $user->phone,
                    'instance'   =>  $this->currentInstance()
                ]);
            }
            $content  = $user->available_cart;
            $res = resolve(CartItemRepositoryInterface::class)->findByVariant($content, $item->variant);
            if ($res) {
                $data = ['quantity' => $res->quantity + 1];
                resolve(CartItemRepositoryInterface::class)->update($res->id, $data);
            } else {

                $data = [
                    'uuid' => $item->rowId,
                    'cart_id' => $user->available_cart->id,
                    'product_id' => $item->product,
                    'variant_id' => $item->variant,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
                resolve(CartItemRepositoryInterface::class)->create($data);
            }
        }

        // if ($dispatchEvent) {
        //     $this->events->dispatch('cart.added', $item);
        // }

        return $item;
    }

    /**
     * Update the cart item with the given rowId.
     *
     * @param string $rowId
     * @param mixed  $quantity
     *
     * @return \Modules\Cart\Services\CartItem
     */
    public function update($rowId, $quantity)
    {
        if (!$this->authenticated) {
            $cartItem =  $this->get($rowId);
            $cartItem->quantity = Arr::get($quantity, 'quantity', $cartItem->quantity);
            $content = $this->getContent();

            if ($cartItem->quantity <= 0) {
                $this->remove($cartItem->id);
                return;
            } else {
                if (isset($itemOldIndex)) {
                    $content = $content->slice(0, $itemOldIndex)
                        ->merge([$cartItem->id => $cartItem])
                        ->merge($content->slice($itemOldIndex));
                } else {

                    $content->put($cartItem->id, $cartItem);
                }
            }

            $this->storage->set($this->instance, serialize($content));
        } else {
            $cartItem = resolve(CartItemRepositoryInterface::class)->update($rowId, $quantity);
            if ($cartItem->quantity <= 0) {
                resolve(CartItemRepositoryInterface::class)->delete($rowId);
            }
        }
        $this->events->dispatch('cart.updated', $cartItem);
        return $cartItem;
    }

    /**
     * Remove the cart item with the given rowId from the cart.
     *
     * @param string $rowId
     *
     * @return void
     */
    public function remove($rowId)
    {
        if (!$this->authenticated) {

            $cartItem = $this->get($rowId);

            $content = $this->getContent();

            $content->pull($cartItem->id);

            $this->storage->set($this->instance, serialize($content));
        } else {
            resolve(CartItemRepositoryInterface::class)->delete($rowId);
        }

        $this->events->dispatch('cart.removed');
    }

    /**
     * Get a cart item from the cart by its rowId.
     *
     * @param string $rowId
     *
     * @return \Modules\Cart\Services\CartItem
     */
    public function get($rowId)
    {
        $content = $this->getContent();
        return  $content->get($rowId);
    }

    /**
     * Destroy the current cart instance.
     *
     * @return void
     */
    public function destroy()
    {
        $this->storage->del($this->instance);
    }

    public function setShipment($value)
    {
        $this->shipment_cost = $value;
    }

    public function setShipmentDiscount($value)
    {
        $this->shipment_discount = $value;
    }

    /**
     * Get the content of the cart.
     *
     * @return \Illuminate\Support\Collection
     */
    public function content($shipping = false)
    {
        $cart_collection = (object)  collect([
            'id' =>  random_int(1000000, 10000000),
            'items' => [],
            'items_count' => 0,
            'payable_price' => 0,
            'rrp_price' => 0,
            "voucher_discount" => 0,
            'items_discount' => 0,
            'shipment_cost' => 0,
            'shipment_discount' => 0,
            'total_discount' => 0,
        ])->toArray();

        if (auth()->check()) {
            $cart = resolve(UserRepositoryInterface::class)->cart();
            if (!is_null($cart)) {
                $cart_items = $cart->items->map(function ($item) {
                    return $this->createCartItem(
                        $item->id,
                        $item->product,
                        $item->variant,
                        $item->variant->calculate_discount,
                        $item->variant->price,
                        $item->variant->weight,
                        $item->quantity
                    );
                });
            } else {
                return $cart_collection;
            }
        } else {
            $cart = null;
            $cart_items = $this->storage->get($this->instance);
            if (is_null($cart_items)) {
                return $cart_collection;
            }
            $cart_items = collect(unserialize($this->storage->get($this->instance)));
            $product_ids = $cart_items->pluck('product')->values()->toArray();
            $variant_ids = $cart_items->pluck('variant')->values()->toArray();
            $products = Product::query()->whereIn('id', $product_ids)->with(['delivery', 'media'])->get();
            $variants = ProductVariant::query()->whereIn('id', $variant_ids)->with(['incredible', 'combinations', 'shipment', 'warranty'])->get();

            $cart_items = $cart_items->map(function ($cart_item, $k) use ($products, $variants) {
                return (object) [
                    'rowId' => $cart_item->rowId, 'id' => $cart_item->id, 'quantity' => $cart_item->quantity, 'price' => $cart_item->price,
                    'weight' => $cart_item->weight, 'options' => $cart_item->options, 'variant' => new ProductVariantResource($variants->where('id', $cart_item->variant)->first()),
                    'product' => new ProductResource($products->where('id', $cart_item->product)->first())
                ];
            })->values()->toArray();
        }



        $cart_items = collect(CartItemsResource::collection($cart_items));
        $cart_items = collect(json_decode($cart_items));

        if ($shipping) {
            $this->shipment($cart);
        }

        $content = (object) [
            'id' => $cart->id ?? random_int(1000000, 10000000),
            'items' => $cart_items->toArray(),
            'items_count' => $this->count($cart_items),
            'rrp_price' => $this->total($cart_items),
            "voucher_discount" => $this->voucher($cart),
            'items_discount' => $this->discount($cart_items),
            'total_discount' => $this->discount($cart_items) + $this->voucher($cart),
            'shipment_cost' => $this->shipment_cost,
            'shipment_discount' => $this->shipment_discount,
            'summary_price' =>  $this->summary($cart_items),
            'payable_price' =>  $this->subtotal($cart_items),
        ];

        return $content;
    }

    /**
     *
     * @return int|float
     */
    public function count($cart)
    {
        return $cart->sum('quantity');
    }

    /**
     * Get the amount of CartItems in the Cart.
     * Keep in mind that this does NOT count quantity.
     *
     * @return int|float
     */
    public function countItems()
    {
        return $this->getContent()->count();
    }


    /**
     * Get the total price of the items in the cart as formatted string.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function total($cart)
    {
        return $cart->reduce(function ($total,  $cartItem) {
            return $total + $cartItem->total;
        }, 0);
    }



    /**
     * Get the total tax of the items in the cart as formatted string.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function tax($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->getContent()->reduce(function ($tax, CartItem $cartItem) {
            return $tax + $cartItem->taxTotal;
        }, 0);
    }


    /**
     * Get the subtotal (total - tax) of the items in the cart as formatted string.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function subtotal($cart)
    {
        $total = $cart->reduce(function ($subTotal,  $cartItem) {
            return $subTotal + $cartItem->subtotal;
        }, 0);
        return $total - $this->voucher_discount + $this->shipment_cost;
    }

    /**
     * Get the subtotal (total - tax)
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function summary($cart)
    {
        $total = $cart->reduce(function ($subTotal,  $cartItem) {
            return $subTotal + $cartItem->subtotal;
        }, 0);
        return $total;
    }



    public function shipment($cart)
    {
        $this->shipment_cost = $cart->shipping_cost ?? 0;
        // $this->shipment_cost =  0;
        return $this->shipment_cost;
    }
    public function voucher($cart)
    {
        $this->voucher_discount = $cart->config->voucher_discount ?? 0;
        return $this->voucher_discount;
    }

    /**
     * Get the discount of the items in the cart as formatted string.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function discount($cart)
    {
        return $cart->reduce(function ($discount, $cartItem) {
            return $discount + $cartItem->discount_total;
        }, 0);
    }


    /**
     * Get the price of the items in the cart as formatted string.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function initial($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {

        return $this->getContent()->reduce(function ($initial, CartItem $cartItem) {
            return $initial + ($cartItem->quantity * $cartItem->price);
        }, 0);
    }


    /**
     * Get the price of the items in the cart as formatted string.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function priceTotal($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {

        return $this->getContent()->reduce(function ($initial, CartItem $cartItem) {
            return $initial + $cartItem->priceTotal;
        }, 0);
    }



    /**
     * Get the total weight of the items in the cart.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function weight($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->getContent()->reduce(function ($total, CartItem $cartItem) {
            return $total + ($cartItem->quantity * $cartItem->weight);
        }, 0);
    }

    /**
     * Search the cart content for a cart item matching the given search closure.
     *
     * @param \Closure $search
     *
     * @return \Illuminate\Support\Collection
     */
    public function search(Closure $search)
    {
        return $this->getContent()->filter($search);
    }

    /**
     * Associate the cart item with the given rowId with the given model.
     *
     * @param string $rowId
     * @param mixed  $model
     *
     * @return void
     */
    public function associate($rowId, $model)
    {
        if (is_string($model) && !class_exists($model)) {
            throw new UnknownModelException("The supplied model {$model} does not exist.");
        }

        $cartItem = $this->get($rowId);

        $cartItem->associate($model);

        $content = $this->getContent();

        $content->put($cartItem->id, $cartItem);

        $this->storage->set($this->instance, serialize($content));
    }

    /**
     * Set the tax rate for the cart item with the given rowId.
     *
     * @param string    $rowId
     * @param int|float $taxRate
     *
     * @return void
     */
    public function setTax($rowId, $taxRate)
    {
        $cartItem = $this->get($rowId);

        $cartItem->setTaxRate($taxRate);

        $content = $this->getContent();

        $content->put($cartItem->id, $cartItem);

        $this->storage->set($this->instance, serialize($content));
    }

    /**
     * Set the global tax rate for the cart.
     * This will set the tax rate for all items.
     *
     * @param float $discount
     */
    public function setGlobalTax($taxRate)
    {
        $this->taxRate = $taxRate;

        $content = $this->getContent();
        if ($content && $content->count()) {
            $content->each(function ($item, $key) {
                $item->setTaxRate($this->taxRate);
            });
        }
    }

    /**
     * Set the discount rate for the cart item with the given rowId.
     *
     * @param string    $rowId
     * @param int|float $taxRate
     *
     * @return void
     */
    public function setDiscount($rowId, $discount)
    {
        $cartItem = $this->get($rowId);

        $cartItem->setDiscountRate($discount);

        $content = $this->getContent();

        $content->put($cartItem->id, $cartItem);

        $this->storage->set($this->instance, serialize($content));
    }

    /**
     * Set the global discount percentage for the cart.
     * This will set the discount for all cart items.
     *
     * @param float $discount
     *
     * @return void
     */
    public function setGlobalDiscount($discount)
    {
        $this->discount = $discount;

        $content = $this->getContent();
        if ($content && $content->count()) {
            $content->each(function ($item, $key) {
                $item->setDiscountRate($this->discount);
            });
        }
    }



    /**
     * Store an the current instance of the cart.
     *
     * @param mixed $identifier
     *
     * @return void
     */
    public function store(string $identifier)
    {
        $content = $this->getContent();

        $instance = $this->currentInstance();

        $user = auth()->user();

        $cart = resolve(CartRepositoryInterface::class)->firstOrCreate([
            'user_id' => $user->id,
            'status' => CartStatus::Available->value
        ], [
            'user_id' => $user->id,
            'identifier' => $identifier,
            'instance'   => $instance,
        ]);

        if ($this->count($content) >= 1) {

            $content->each(function ($item, $key) use ($cart) {

                $variant = resolve(ProductVariantRepositoryInterface::class)->find($item->variant);
                $order_limit = $variant->order_limit;
                $exists = resolve(CartItemRepositoryInterface::class)->firstOrCreate(
                    ['variant_id' => $item->variant],
                    [
                        'uuid' => $item->rowId,
                        'cart_id' => $cart->id,
                        'product_id' => $item->product,
                        'variant_id' => $item->variant,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                    ]
                );
            });



            $this->events->dispatch('cart.stored');
            $this->destroy();
        }
    }

    /**
     * Restore the cart with the given identifier.
     *
     * @param mixed $identifier
     *
     * @return void
     */
    public function restore($identifier)
    {
        if ($identifier instanceof InstanceIdentifier) {
            $identifier = $identifier->getInstanceIdentifier();
        }

        $currentInstance = $this->currentInstance();

        if (!$this->storedCartInstanceWithIdentifierExists($currentInstance, $identifier)) {
            return;
        }

        $stored = $this->getConnection()->table($this->getTableName())
            ->where(['identifier' => $identifier, 'instance' => $currentInstance])->first();

        if ($this->getConnection()->getDriverName() === 'pgsql') {
            $storedContent = unserialize(base64_decode(data_get($stored, 'content')));
        } else {
            $storedContent = unserialize(data_get($stored, 'content'));
        }

        $this->instance(data_get($stored, 'instance'));

        $content = $this->getContent();

        foreach ($storedContent as $cartItem) {
            $content->put($cartItem->id, $cartItem);
        }

        $this->events->dispatch('cart.restored');

        $this->storage->set($this->instance, serialize($content));

        $this->instance($currentInstance);

        $this->createdAt = Carbon::parse(data_get($stored, 'created_at'));
        $this->updatedAt = Carbon::parse(data_get($stored, 'updated_at'));

        $this->getConnection()->table($this->getTableName())->where(['identifier' => $identifier, 'instance' => $currentInstance])->delete();
    }

    /**
     * Erase the cart with the given identifier.
     *
     * @param mixed $identifier
     *
     * @return void
     */
    public function erase($identifier)
    {
        if ($identifier instanceof InstanceIdentifier) {
            $identifier = $identifier->getInstanceIdentifier();
        }

        $instance = $this->currentInstance();

        if (!$this->storedCartInstanceWithIdentifierExists($instance, $identifier)) {
            return;
        }

        $this->getConnection()->table($this->getTableName())->where(['identifier' => $identifier, 'instance' => $instance])->delete();

        $this->events->dispatch('cart.erased');
    }

    /**
     * Merges the contents of another cart into this cart.
     *
     * @param mixed $identifier   Identifier of the Cart to merge with.
     * @param bool  $keepDiscount Keep the discount of the CartItems.
     * @param bool  $keepTax      Keep the tax of the CartItems.
     * @param bool  $dispatchAdd  Flag to dispatch the add events.
     *
     * @return bool
     */
    public function merge($identifier, $keepDiscount = false, $keepTax = false, $dispatchAdd = true, $instance = self::DEFAULT_INSTANCE)
    {
        if (!$this->storedCartInstanceWithIdentifierExists($instance, $identifier)) {
            return false;
        }

        $stored = $this->getConnection()->table($this->getTableName())
            ->where(['identifier' => $identifier, 'instance' => $instance])->first();

        if ($this->getConnection()->getDriverName() === 'pgsql') {
            $storedContent = unserialize(base64_decode($stored->content));
        } else {
            $storedContent = unserialize($stored->content);
        }

        foreach ($storedContent as $cartItem) {
            $this->addCartItem($cartItem, $keepDiscount, $keepTax, $dispatchAdd);
        }

        $this->events->dispatch('cart.merged');

        return true;
    }

    /**
     * Magic method to make accessing the total, tax and subtotal properties possible.
     *
     * @param string $attribute
     *
     * @return float|null
     */
    // public function __get($attribute)
    // {
    //     switch ($attribute) {
    //         case 'total':
    //             return $this->total();
    //         case 'tax':
    //             return $this->tax();
    //         case 'subtotal':
    //             return $this->subtotal();
    //         default:
    //             return;
    //     }
    // }

    /**
     * Get the carts content, if there is no cart content set yet, return a new empty Collection.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getContent()
    {
        if ($this->storage->exists($this->instance)) {
            return collect(unserialize($this->storage->get($this->instance)));
        }

        return new Collection();
    }

    /**
     * Create a new CartItem from the supplied attributes.
     *
     * @param mixed     $id
     * @param mixed     $name
     * @param int|float $quantity
     * @param float     $price
     * @param float     $weight
     * @param array     $options
     *
     * @return \Modules\Cart\Services\CartItem
     */
    private function createCartItem($id, $product, $variant, $discount, $price, $weight, $quantity, array $options = [])
    {

        $cartItem = (object) array(
            "rowId" => md5($id),
            "id" => $id,
            "product" => $product,
            "variant" => $variant,
            "quantity" => $quantity,
            "price" => $price,
            "weight" => $weight,
            "options" => $options,
        );
        return $cartItem;
    }

    /**
     * Check if the item is a multidimensional array or an array of Buyables.
     *
     * @param mixed $item
     *
     * @return bool
     */
    private function isMulti($item)
    {
        if (!is_array($item)) {
            return false;
        }

        return is_array(head($item)) || head($item) instanceof Buyable;
    }

    /**
     * @param $identifier
     *
     * @return bool
     */
    private function storedCartInstanceWithIdentifierExists($instance, $identifier)
    {
        return $this->getConnection()->table($this->getTableName())->where(['identifier' => $identifier, 'instance' => $instance])->exists();
    }

    /**
     * Get the database connection.
     *
     * @return \Illuminate\Database\Connection
     */
    private function getConnection()
    {
        return app(DatabaseManager::class)->connection($this->getConnectionName());
    }

    /**
     * Get the database table name.
     *
     * @return string
     */
    private function getTableName()
    {
        return config('cart.database.table', 'cart_items');
    }

    /**
     * Get the database connection name.
     *
     * @return string
     */
    private function getConnectionName()
    {
        $connection = config('cart.database.connection');

        return is_null($connection) ? config('database.default') : $connection;
    }

    /**
     * Get the creation date of the cart (db context).
     *
     * @return \Carbon\Carbon|null
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    /**
     * Get the lats update date of the cart (db context).
     *
     * @return \Carbon\Carbon|null
     */
    public function updatedAt()
    {
        return $this->updatedAt;
    }
}
