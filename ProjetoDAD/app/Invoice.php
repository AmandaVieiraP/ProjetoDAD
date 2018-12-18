<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function meal()
    {
        return $this->hasOne(Meal::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

}
