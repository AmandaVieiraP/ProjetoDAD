<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\InvoiceItem;
use App\Http\Resources\InvoiceItem as InvoiceItemResource;

class InvoiceItemControllerAPI extends Controller
{

    public function getInvoicesItems($id) {
        $items = InvoiceItem::where('invoice_id', '=', $id)->join('items', 'items.id','=', 'invoice_items.item_id')->get();

        return InvoiceItemResource::collection($items);
    }

}
