<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Order as OrderResource;
use App\Order;
use Response;

class OrderControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateState(Request $request, $id){

        $order=Order::findOrFail($id);

        if(($order->state != "in preparation" && $order->state == "confirmed") &&
            ($order->state == "in preparation" && $request->input('state') != "prepared") && 
            ($order->state == "confirmed" && $request->input('state') != "in preparation")){
           
           return Response::json( ['error' => 'Invalid state to update'], 422);
        }

        $order->state=$request->input('state');

        $order->save();

        return new OrderResource($order);
    }
}
