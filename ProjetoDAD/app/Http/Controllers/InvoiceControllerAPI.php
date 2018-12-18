<?php

namespace App\Http\Controllers;

use App\InvoiceItem;
use Illuminate\Http\Request;
use App\Http\Resources\Invoice as InvoiceResource;
use App\Http\Resources\InvoiceItem as InvoiceItemResource;
use App\Invoice;

class InvoiceControllerAPI extends Controller
{
/*
    public function getPendingInvoices() {
        $pendingInvoices = Invoice::where('state', '=', 'pending')->get();
        return InvoiceResource::collection($pendingInvoices);
    }  */

    /*public function getResponsibleWaiterForPendingInvoices() {

        return WaiterResource
    } */


    public function getPendingInvoicesWithWaiter() {
        $pendingInvoices = Invoice::join('meals', 'invoices.meal_id', '=', 'meals.id')
            ->join('users', 'users.id', '=', 'meals.responsible_waiter_id')->where('invoices.state', '=', 'pending')
            ->get(['invoices.*', 'meals.responsible_waiter_id', 'users.name as waiterName']);
        return InvoiceResource::collection($pendingInvoices);
    }

    public function getInvoicesItems($id) {
        $invoice = Invoice::findOrFail($id);
       // $invoice = Invoice::findOrFail($id)->with('items')->get();
        // PostModel::with('comments')->find($id);
       // $items = $invoice->items;
        //dd($invoice->items()->get());
        $items = $invoice->items()->join('items', 'items.id','=', 'invoice_items.item_id')->get();
       // $items2 = $items->
       // $items = $invoice->items()->item()->get();
      //  dd($invoice);
      //  dd($items);

        // $items = InvoiceItem->where('invoice_id', '=', $id);
        return InvoiceItemResource::collection($items);
    }


}
