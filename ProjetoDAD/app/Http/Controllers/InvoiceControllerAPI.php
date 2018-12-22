<?php

namespace App\Http\Controllers;

use App\Meal;
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

    public function createInvoice($mealId) {
        $meal = Meal::findOrFail($mealId);

        $invoice = new Invoice();
        //state','meal_id','nif', 'name', 'date', 'total_price'
        $invoice->state = 'pending';
        $invoice->meal_id = $meal->id;
        $invoice->date = date('Y-m-d H:m:s');
        $invoice->total_price = $meal->total_price_preview;
        $invoice->save();


        return new InvoiceResource($invoice);
    }


    public function payInvoice(Request $request, $id) {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'nif' => 'required|digits:9|numeric',
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
        ]);

        $invoice->state = "paid";
        $invoice->nif = $request->input('nif');
        $invoice->name = $request->input('name');
        $invoice->save();

        $meal = Meal::findOrFail($invoice->meal_id);
        $meal->state = "paid";
        $meal->save();

        return new InvoiceResource($invoice);
    }

}
