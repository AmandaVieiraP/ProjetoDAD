<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Invoice as InvoiceResource;
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
        //>join('contacts', 'users.id', '=', 'contacts.user_id')
      //  $pendingInvoices = Invoice::where('state', '=', 'pending')->get();
        //::join('meals', 'orders.meal_id', '=', 'meals.id')

        //['students.*', 'managers.name as managers_name', 'managers.surname as managers_surname', 'managers.other_column_if_needed']);
        $pendingInvoices = Invoice::join('meals', 'invoices.meal_id', '=', 'meals.id')
            ->join('users', 'users.id', '=', 'meals.responsible_waiter_id')->where('invoices.state', '=', 'pending')
            ->get(['invoices.*', 'meals.responsible_waiter_id', 'users.name']);
        return InvoiceResource::collection($pendingInvoices);
    }


}
