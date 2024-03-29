<?php

namespace Modules\User\Entities;

use Spatie\Image\Manipulations;
use Modules\State\Entities\City;
use Spatie\MediaLibrary\HasMedia;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Cart\Services\Cart\Facades\Cart;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Modules\Cart\Transformers\App\CartResource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Cart\Entities\Cart as EntitiesCart;
use Modules\Cart\Entities\Shipping;
use Modules\Cart\Enums\CartStatus;
use Modules\Order\Entities\Order;
use Modules\User\Database\factories\UserFactory;
use Modules\Voucher\Entities\Voucher;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, InteractsWithMedia, HasRoles;

    protected $fillable = [
        'city_id', 'username', 'wallet', 'ip', 'point', 'email', 'phone', 'email_verified_at', 'password',
        'status', 'job', 'national_identity_number', 'gender', 'cart_number', 'iban', 'is_superuser',
    ];


    //    protected $guard_name = 'web';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function findForPassport($username)
    {
        return self::where('phone', $username)->first();
    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param  string  $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return true;
    }

    public function shippings()
    {
        return $this->hasMany(Shipping::class);
    }


    public function city()
    {
        return $this->belongsTo(City::class)->with('state');
    }

    public function cart()
    {
        return $this->hasOne(EntitiesCart::class)->where('status', CartStatus::Available->value);
    }

    public function carts()
    {
        return $this->hasMany(EntitiesCart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public static function last()
    {
        return static::all()->last();
    }



    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(250)
            ->height(250);
    }

    protected function availableCart(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->carts()->where('status', CartStatus::Available->value)
                ->with(['items' => ['product' => ['delivery', 'media'], 'variant' => ['incredible']]])
                ->first()
        );
    }

    /**
     * Check user is login .
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function isLoggedIn(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !!auth()->user()
        );
    }


    /**
     * Calculate discount percent.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function hasPassword(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !!$this->password,
        );
    }


    /**
     * Get default address.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function defaultAddress(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->addresses()->where('is_default', true)->first(),
        );
    }

    /**
     * Check default address.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function hasDefaultAddress(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->addresses()->where('is_default', true)->exists(),
        );
    }


    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Get all of the vouchers for the user.
     */
    public function vouchers()
    {
        return $this->morphToMany(Voucher::class, 'voucherable');
    }


    public function isSuperUser()
    {
        return $this->is_superuser;
    }
}
