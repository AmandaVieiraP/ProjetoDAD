<?php

namespace App\Http\Controllers;


use Doctrine\DBAL\Schema\Table;
use Illuminate\Http\Request;

use App\Http\Resources\RestaurantTable as TableResource;
use App\Http\Resources\Meal as MealResource;
use App\RestaurantTable;
use App\Meal;


class MealControllerAPI extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return UserResource::collection(User::all());
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
    //US5
    public function update(Request $request, $id)
    {
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

    public function getNonActiveTables(){

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



}
