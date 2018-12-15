<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    //
    protected $fillable = [
        'state','table_number','start','end','responsible_waiter_id', 'total_price_preview',
    ];


    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orders_delivered()
    {
        return $this->hasMany(Order::class)->where('state', 'delivered');
    }

}
