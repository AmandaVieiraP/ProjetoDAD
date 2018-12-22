<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Order;
use App\Notifications\EmailVerification;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password', 'type', 'photo_url',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //US9 - relacionamento entre Cooker e Order (1 cooker muitas orders)
    public function orders()
    {
        return $this->hasMany(Order::class,'responsible_cook_id');
    }

    public function sendEmailVerificationNotification() {
        $this->notify(new EmailVerification());
    }


    public function meals()
    {
        return $this->hasMany(Meal::class,'responsible_waiter_id');
    }
}
