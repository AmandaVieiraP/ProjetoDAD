<?php

namespace App\Http\Controllers;

use App\Meal;
use Illuminate\Http\Request;
use App\Http\Resources\Invoice as InvoiceResource;
use App\Invoice;
use App\InvoiceItem;
Use PDF;
use Response;

class InvoiceControllerAPI extends Controller
{
    public function pendingInvoicesWithWaiter() {
        $pendingInvoices = Invoice::join('meals', 'invoices.meal_id', '=', 'meals.id')
        ->join('users', 'users.id', '=', 'meals.responsible_waiter_id')->where('invoices.state', '=', 'pending')
        ->get(['invoices.*', 'meals.responsible_waiter_id',  'meals.table_number','users.name as waiterName']);
        return InvoiceResource::collection($pendingInvoices);
    }

    public function paidInvoices() {
        $paidInvoices = Invoice::where('state', '=', 'paid')->get();

        return InvoiceResource::collection($paidInvoices);
    }

    public function createInvoice($mealId) {
        $meal = Meal::findOrFail($mealId);

        $invoice = new Invoice();
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

    public function updateState(Request $request, $id){

        $invoice=Invoice::findOrFail($id);

        if(($invoice->state == "paid"  || $invoice->state == "not paid"))
        {

            return Response::json( ['error' => 'Invalid state to update'], 422);
        }

        $invoice->state=$request->input('state');
        if($request->input('state') == "paid" || $request->input('state') == "not paid" )
        {
            $invoice->date = date('Y-m-d H:m:s');
        }

        $invoice->save();

        return new InvoiceResource($invoice);
    }

    public function downloadInvoicePdf( $id) {

        $i= Invoice::join('meals', 'invoices.meal_id', '=', 'meals.id')
        ->join('users', 'users.id', '=', 'meals.responsible_waiter_id')->where('invoices.id', '=', $id)
        ->get(['invoices.*', 'meals.responsible_waiter_id', 'users.name as waiterName']);
        $invoice = $i[0];
        $items = InvoiceItem::where('invoice_id', '=', $invoice->id)->join('items', 'items.id','=', 'invoice_items.item_id')->get();

        $pdf = PDF::loadView('pdf.invoicePDF', compact(['invoice', 'items']));
        return $pdf->download('invoice.pdf');
    }

    public function index( ) {
        $invoices = Invoice::join('meals', 'invoices.meal_id', '=', 'meals.id')
        ->join('users', 'users.id', '=', 'meals.responsible_waiter_id')
        ->get(['invoices.*', 'meals.responsible_waiter_id',  'meals.table_number','users.name as waiterName']);
        return InvoiceResource::collection($invoices);
    }


}
