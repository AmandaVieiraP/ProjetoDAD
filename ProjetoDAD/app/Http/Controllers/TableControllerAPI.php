<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RestaurantTable;
use App\Http\Resources\RestaurantTable as RestaurantTableResource;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Http\Resources\Meal as MealResource;

class TableControllerAPI extends Controller
{
    //US28
    public function index()
    {
        return RestaurantTableResource::collection(RestaurantTable::all());
    }

    //US28
    public function store(Request $request)
    {
        if(Auth::guard('api')->user()->type != 'manager'){
            return Response::json([
                'unauthorized' => 'Access forbiden! Only managers are allowed'
            ], 401);
        }

        $request->validate([
            'table_number'=>'required|integer|min:1|unique:restaurant_tables',
        ]);

        $table = new RestaurantTable();
        $table->table_number = $request->input('table_number');

        $table->save();

        return new RestaurantTableResource($table);
    }

    public function show($id)
    {
        //
    }

    //US28
    public function update(Request $request, $id)
    {
        if(Auth::guard('api')->user()->type != 'manager'){
            return Response::json([
                'unauthorized' => 'Access forbiden! Only managers are allowed'
            ], 401);
        }

        $validateData=$request->validate([
            'table_number'=>'required|integer|min:1|unique:restaurant_tables',
        ]);

        $table=RestaurantTable::findOrFail($id);

        $meals=$table->meals;

        //só altera se não tiver refeições associadas
        if(!$meals->isEmpty()){

            return Response::json([
                'error' => 'Can not change the number of the table because it has meals associated'
            ], 422);
        }

        $table->fill($validateData)->save();

        return new RestaurantTableResource($table);
    }

    //US28
    public function destroy($id)
    {
        if(Auth::guard('api')->user()->type != 'manager'){
            return Response::json([
                'unauthorized' => 'Access forbiden! Only managers are allowed'
            ], 401);
        }

        $table = RestaurantTable::findOrFail($id);

        $meals = $table->meals;

        if($meals->isEmpty()){
            $table->forceDelete();
            return new RestaurantTableResource($table);
        }

        //soft delete
        $table->delete();
        return new RestaurantTableResource($table);

    }
}
