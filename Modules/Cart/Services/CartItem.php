<?php

namespace Modules\Cart\Services;

use ReflectionClass;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Cart\Contracts\Buyable;
use Modules\Product\Entities\Variant;
use Modules\Cart\Contracts\Calculator;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Modules\Product\Entities\ProductVariant;
use Modules\Cart\Calculation\DefaultCalculator;
use Modules\Cart\Exceptions\InvalidCalculatorException;


/**
 * @property-read mixed discount
 * @property-read float discountTotal
 * @property-read float priceTarget
 * @property-read float priceNet
 * @property-read float priceTotal
 * @property-read float subtotal
 * @property-read float taxTotal
 * @property-read float tax
 * @property-read float total
 * @property-read float priceTax
 */
class CartItem implements Arrayable, Jsonable
{

    public $product;
    public $variant;
    /**
     * The rowID of the cart item.
     *
     * @var string
     */
    public $rowId;

    /**
     * The ID of the cart item.
     *
     * @var int|string
     */
    public $id;

    /**
     * The quantity for this cart item.
     *
     * @var int|float
     */
    public $quantity;

    /**
     * The name of the cart item.
     *
     * @var string
     */
    public $name;

    /**
     * The price without TAX of the cart item.
     *
     * @var float
     */
    public $price;

    /**
     * The weight of the product.
     *
     * @var float
     */
    public $weight;

    /**
     * The options for this cart item.
     *
     * @var CartItemOptions|array
     */
    public $options;

    /**
     * The tax rate for the cart item.
     *
     * @var int|float
     */
    public $taxRate = 0;

    /**
     * The FQN of the associated model.
     *
     * @var string|null
     */
    private $associatedModel = null;

    /**
     * The discount rate for the cart item.
     *
     * @var float
     */
    private $discountRate = 0;

    /**
     * The cart instance of the cart item.
     *
     * @var null|string
     */
    public $instance = null;



    /**
     * CartItem constructor.
     *
     * @param int|string $id
     * @param string     $name
     * @param float      $price
     * @param float      $weight
     * @param array      $options
     */
    public function __construct(
        $id,
        $product,
        $variant,
        $discount,
        $price,
        $weight = 0,
        array $options = [],

    ) {

        if (empty($id)) {
            throw new \InvalidArgumentException('Please supply a valid identifier.');
        }
        if (empty($product)) {
            throw new \InvalidArgumentException('Please supply a valid name.');
        }
        if (strlen($price) < 0 || !is_numeric($price)) {
            throw new \InvalidArgumentException('Please supply a valid price.');
        }
        if (strlen($weight) < 0 || !is_numeric($weight)) {
            throw new \InvalidArgumentException('Please supply a valid weight.');
        }

        $this->id = $id;
        // $this->name = $name;
        $this->product = $product;
        $this->variant = $variant;
        // $this->discount = $discount;
        $this->price = floatval($price);
        $this->weight = floatval($weight);
        // $this->discountRate = 50;
        $this->options = new CartItemOptions($options);
        $this->rowId = $this->generateRowId($id, $options);
    }

    /**
     * Returns the formatted weight.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function weight($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->weight, $decimals, $decimalPoint, $thousandSeperator);
    }




    /**
     * Returns the formatted price without TAX.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function price($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->price, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns the formatted price with discount applied.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function priceTarget($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->priceTarget, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns the formatted price with TAX.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function priceTax($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->priceTax, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns the formatted subtotal.
     * Subtotal is price for whole CartItem without TAX.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function subtotal($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->subtotal, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns the formatted total.
     * Total is price for whole CartItem with TAX.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function total($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->total, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns the formatted tax.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function tax($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->tax, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns the formatted tax.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function taxTotal($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->taxTotal, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns the formatted discount.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function discount($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->discount, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns the formatted total discount for this cart item.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function discountTotal($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->discountTotal, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns the formatted total price for this cart item.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function priceTotal($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->priceTotal, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Set the quantity for this cart item.
     *
     * @param int|float $quantity
     */
    public function setQuantity($quantity)
    {
        if (empty($quantity) || !is_numeric($quantity)) {
            throw new \InvalidArgumentException('Please supply a valid quantity.');
        }

        $this->quantity = $quantity;
    }

    /**
     * Update the cart item from a Buyable.
     *
     * @param \Modules\Cart\Contracts\Buyable $item
     *
     * @return void
     */
    public function updateFromBuyable(Buyable $item)
    {
        $this->id = $item->getBuyableIdentifier($this->options);
        $this->name = $item->getBuyableDescription($this->options);
        $this->price = $item->getBuyablePrice($this->options);
    }

    /**
     * Update the cart item from an array.
     *
     * @param array $attributes
     *
     * @return void
     */
    public function updateFromArray(array $attributes)
    {
        $this->id = Arr::get($attributes, 'id', $this->id);
        $this->quantity = Arr::get($attributes, 'quantity', $this->quantity);
        $this->name = Arr::get($attributes, 'name', $this->name);
        $this->price = Arr::get($attributes, 'price', $this->price);
        $this->variant = Arr::get($attributes, 'variant', $this->variant);
        $this->weight = Arr::get($attributes, 'weight', $this->weight);
        $this->discountRate = Arr::get($attributes, 'discount', $this->discount);
        $this->options = new CartItemOptions(Arr::get($attributes, 'options', $this->options));
        $this->rowId = $this->generateRowId($this->id, $this->options->all());
    }

    /**
     * Associate the cart item with the given model.
     *
     * @param mixed $model
     *
     * @return \Modules\Cart\Services\CartItem
     */
    public function associate($model)
    {
        $this->associatedModel = is_string($model) ? $model : get_class($model);
        return $this;
    }

    /**
     * Set the tax rate.
     *
     * @param int|float $taxRate
     *
     * @return \Modules\Cart\Services\CartItem
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * Set the discount rate.
     *
     * @param int|float $discountRate
     *
     * @return \Modules\Cart\Services\CartItem
     */
    public function setDiscountRate($discountRate)
    {
        $this->discountRate = $discountRate;

        return $this;
    }

    /**
     * Set cart instance.
     *
     * @param null|string $instance
     *
     * @return \Modules\Cart\Services\CartItem
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get an attribute from the cart item or get the associated model.
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function __get($attribute)
    {
        if (property_exists($this, $attribute)) {
            return $this->{$attribute};
        }
        $decimals = config('cart.format.decimals', 2);

        switch ($attribute) {
            case 'model':
                if (isset($this->associatedModel)) {
                    return with(new $this->associatedModel())->find($this->id);
                }
                // no break
            case 'modelFQCN':
                if (isset($this->associatedModel)) {
                    return $this->associatedModel;
                }
                // no break
            case 'weightTotal':
                return round($this->weight * $this->quantity, $decimals);
        }

        $class = new ReflectionClass(config('cart.calculator', DefaultCalculator::class));
        if (!$class->implementsInterface(Calculator::class)) {
            throw new InvalidCalculatorException('The configured Calculator seems to be invalid. Calculators have to implement the Calculator Contract.');
        }

        return call_user_func($class->getName() . '::getAttribute', $attribute, $this);
    }

    /**
     * Create a new instance from a Buyable.
     *
     * @param \Modules\Cart\Contracts\Buyable $item
     * @param array                                      $options
     *
     * @return \Modules\Cart\Services\CartItem
     */
    public static function fromBuyable(Buyable $item, array $options = [])
    {
        return new self($item->getBuyableIdentifier($options), $item->getBuyableDescription($options), $item->getBuyablePrice($options), $item->getBuyableWeight($options), $options);
    }

    /**
     * Create a new instance from the given array.
     *
     * @param array $attributes
     *
     * @return \Modules\Cart\Services\CartItem
     */
    public static function fromArray(array $attributes)
    {
        $options = Arr::get($attributes, 'options', []);

        return new self($attributes['id'], $attributes['product'], $attributes['variant'],  $attributes['discount'], $attributes['price'], $attributes['weight'], $options);
    }

    /**
     * Create a new instance from the given attributes.
     *
     * @param int|string $id
     * @param string     $name
     * @param float      $price
     * @param array      $options
     *
     * @return \Modules\Cart\Services\CartItem
     */
    public static function fromAttributes($id, $product, $variant, $discount, $price, $weight, $quantity, array $options = [])
    {
        return new self($id, $product, $variant, $discount, $price, $weight, $options);
    }

    /**
     * Generate a unique id for the cart item.
     *
     * @param string $id
     * @param array  $options
     *
     * @return string
     */
    protected function generateRowId($id, array $options)
    {
        ksort($options);

        return md5($id);
        // return Str::random(16);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'rowId'    => $this->rowId,
            'id'       => $this->id,
            'product'     => $this->product,
            'variant'     => $this->variant,
            'quantity'      => $this->quantity,
            'price'    => $this->price,
            'weight'   => $this->weight,
            'options'  => is_object($this->options)
                ? $this->options->toArray()
                : $this->options,
            'discount' => $this->discount,
            // 'tax'      => $this->tax,
            'subtotal' => $this->subtotal,
            'total' => $this->total,

        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get the formatted number.
     *
     * @param float  $value
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    private function numberFormat($value, $decimals, $decimalPoint, $thousandSeperator)
    {
        if (is_null($decimals)) {
            $decimals = config('cart.format.decimals', 2);
        }

        if (is_null($decimalPoint)) {
            $decimalPoint = config('cart.format.decimal_point', '.');
        }

        if (is_null($thousandSeperator)) {
            $thousandSeperator = config('cart.format.thousand_separator', ',');
        }

        return number_format($value, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Getter for the raw internal discount rate.
     * Should be used in calculators.
     *
     * @return float
     */
    public function getDiscountRate()
    {
        return $this->discountRate;
    }
}
