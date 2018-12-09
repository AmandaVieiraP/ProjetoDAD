<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Order as OrderResource;
use App\Order;
use App\User;
use Response;
use App\Meal;

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
        $order=Order::findOrFail($id);
        if($order->state == 'pending') {
            $order->delete();
        }
        else{
            return Response::json( ['error' => 'Only possible to remove an pending order'], 422);
        }


        return new OrderResource($order);

    }

    public function getUnsignedOrders(){

        $orders = Order::whereNull('responsible_cook_id')->where('state','!=','pending')->get();

        return new OrderResource($orders);
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

public function createOrder(Request $request){



    $request->validate([
        'state' => 'required|',
        'meal_id' => 'required|regex:/(^[0-9\+ ]+$)+/',
        'item_id' => 'required|regex:/(^[0-9\+ ]+$)+/',
    ]
);

    $order = new Order();
    $order->state = $request->state;
    $order->meal_id = $request->meal_id;
    $order->item_id = $request->item_id;
    $order->start = date('Y-m-d H:m:s');

    $order->save();


        /* //atualizar a meal e somar ao valor que ja tem o preco deste item novo
        $meal = Meal::findOrFail($request->meal_id);

        $meal->total_price_preview = $meal->total_price_preview + $request->total_price_preview;

        $meal->save();*/

        return new OrderResource($order);
    }

    public function updateCook(Request $request, $id){

        $order=Order::findOrFail($id);

        if($order->state == "pending" || $order->responsible_cook_id != null){
         return Response::json( ['error' => "Can't set a cook to this order"], 422);
     }

     $cook=User::findOrFail($request->input('cook'));

     $order->responsible_cook_id=$request->input('cook');

     $order->save();

     return new OrderResource($order);
 }
}
