<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Response;
use App\Http\Resources\RestaurantTable as TableResource;
use App\Http\Resources\Meal as MealResource;
use App\RestaurantTable;
use App\Meal;


class MealControllerAPI extends Controller
{
    public function index()
    {
        return MealResource::collection(Meal::all());
    }

    public function show($id)
    {
        $meal=Meal::findOrFail($id);

        return new MealResource($meal);
    }

    public function itemsFromMeal($id){
        $meal=Meal::findOrFail($id);

        $orders=$meal->orders;

        $items=[];


        foreach ($orders as $order) {
            $item=$order->item;

            if($item != null){
                $a=array(
                    "id"=>$item['id'],
                    "type"=>$item['type'],
                    "name"=>$item['name'],
                    "price"=>$item['price'],
                    "order_state"=>$order['state'],
                    "order_id"=>$order['id']);
                
                array_push($items, $a);
            }
            
        }

        return Response::json($items, 200);
    }

    public function createMeal(Request $request) {
        $request->validate([
            'state' => 'required|',
            'table_number' => 'required|regex:/(^[0-9\+ ]+$)+/',
            'responsible_waiter_id' => 'required|regex:/(^[0-9\+ ]+$)+/',
        ]   );

        $meal = new Meal();
        $meal->state = $request->state;
        $meal->table_number = $request->table_number;
        $meal->responsible_waiter_id = $request->responsible_waiter_id;
        $meal->start = date('Y-m-d H:m:s');

        $meal->save();


        return new MealResource($meal);
    }

    public function updateTotalPrice(Request $request) {
        $meal = Meal::findOrFail($request->meal_id);

        $meal->total_price_preview = $meal->total_price_preview + $request->total_price_preview;

        $meal->save();

        return new MealResource($meal);
    }

    public function nonActiveTables(){

        //buscar todas as tables
        $tables = RestaurantTable::select(
            'restaurant_tables.table_number'
        )->get();

        //buscar as active
        $nonActiveTables = Meal::where('state', '=', 'active')->select(
            'meals.table_number'
        )->get();

        $outputTables =  RestaurantTable::whereIn('table_number',$tables)->whereNotIn('table_number',$nonActiveTables)->get();

        return TableResource::collection($outputTables);
    }

    public function activeOrTeminatedMeals(){

        $meals =  Meal::where('state', '=', 'active')->orWhere('state', '=', 'terminated')->get();

        return MealResource::collection($meals);
    }

    public function myMeals($id){

        $myMeals = Meal::where('responsible_waiter_id', '=', $id)->where('state', '=', 'active')->get();
        return MealResource::collection($myMeals);
    }

    public function terminateMeal($id){
        $meal = Meal::findOrFail($id);

        $meal->state = 'terminated';
        $meal->end = date('Y-m-d H:m:s');

        $orders = $meal->orders->where('state', '<>', 'delivered');

        foreach($orders as $order){
           $order->state = 'not delivered';
           $order->end = date('Y-m-d H:m:s');
           $meal->total_price_preview = $meal->total_price_preview - $order->item->price;
           $order->save();
       }

       $meal->save();
       return new MealResource($meal);
   }

   public function mealFromOrder($id){
        $meal = Meal::join('orders', 'meals.id', '=', 'orders.meal_id')->where('orders.id','=',$id)->get();

        return new MealResource($meal);
    }

    public function markMealAsNotPaid($id){

        $meal = Meal::findOrFail($id);

        $meal->state = 'not paid';

        $orders = $meal->orders->where('state', '<>', 'delivered');

        foreach($orders as $order){
            $order->state = 'not delivered';
            $order->end = date('Y-m-d H:m:s');
            $order->save();
        }

        $meal->save();
        $meal = Meal::join('invoices', 'meals.id', '=', 'invoices.meal_id')->where('meals.id','=',$id)->select(
            'invoices.id'
        )->get();

        return new MealResource($meal);
    }
}
