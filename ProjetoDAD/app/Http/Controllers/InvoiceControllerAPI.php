<?php

namespace App\Http\Controllers;

use App\InvoiceItem;
use Illuminate\Http\Request;
use App\Http\Resources\Invoice as InvoiceResource;
use App\Http\Resources\InvoiceItem as InvoiceItemResource;
use App\Invoice;

class InvoiceControllerAPI extends Controller
{


    public function getPendingInvoicesWithWaiter() {
        $pendingInvoices = Invoice::join('meals', 'invoices.meal_id', '=', 'meals.id')
            ->join('users', 'users.id', '=', 'meals.responsible_waiter_id')->where('invoices.state', '=', 'pending')
            ->get(['invoices.*', 'meals.responsible_waiter_id', 'users.name as waiterName']);
        return InvoiceResource::collection($pendingInvoices);
    }
 /*
    public function getInvoicesItems($id) {
        $invoice = Invoice::findOrFail($id);

        $items = $invoice->items()->join('items', 'items.id','=', 'invoice_items.item_id')->get();

        return InvoiceItemResource::collection($items);
    }
*/

}
