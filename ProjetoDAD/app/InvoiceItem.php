<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;

class InvoiceItem extends Model
{
	protected $fillable = [
        'invoice_id','item_id','quantity','unit_price','sub_total_price'
    ];

    //US28
    public function item()
    {
        return $this->belongsTo(Item::class);
    }	
}
