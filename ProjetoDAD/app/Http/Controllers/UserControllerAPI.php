<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Order as OrderResource;
use Hash;
use App\StoreUserRequest;
use App\User;
use App\Order;
use Response;
use App\Mail\EmailSender;
use Illuminate\Auth\Events\Registered;
use App\Http\Resources\RestaurantTable as RestaurantTableResource;
use App\Table;

class UserControllerAPI extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $user=User::findOrFail($id);
        return new UserResource($user);
    }

    //US4 
    public function changePassword(Request $request, $id){

        $request->validate([
            'old_password'=>'required',
            'password'=>'required|confirmed|min:3|different:old_password',
            'password_confirmation'=>'required|same:password',
        ]);

        $user=User::findOrFail($id);

        if(Auth::guard('api')->user()->id != $user->id){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        if (!(Hash::check($request->input('old_password'), $user->password))) {
            return Response::json([
                'old_password' => 'Please enter the correct current password'
            ], 422);
        }


        $user->password=Hash::make($request->input('password'));
        
        $user->save();

        return new UserResource($user);
    }

    //US5
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'username' => 'required|regex:/^[a-zA-Z0-9]+([._]?[a-zA-Z0-9]+)*$/',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'email' => 'nullable|email',
        ]);

        $user = User::findOrFail($id);

       if((Auth::guard('api')->user()->id != $user->id) && (Auth::guard('api')->user()->type != 'manager')){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        if (User::where('username', '=', $request->username)->where('id', '<>', $id)->exists()) {
            // user found
            return Response::json([
                'user_already_exists' => 'Already exists a user with that username!'
            ], 422);
        }

        if (User::where('email', '=', $request->email)->where('id', '<>', $id)->exists()) {
            // user found
            return Response::json([
                'user_already_exists' => 'Already exists a user with that email!'
            ], 422);
        }


        if($request->photo != null) {
            $image = $request->file('photo');
            $path = basename($image->store('profiles', 'public'));
            $user->photo_url = basename($path);
        }

        $user->name = $request->name;
        $user->username = $request->username;


        $user->save();
        //$user->update($request->all());
        return new UserResource($user);

    }

    //US30
    public function destroy($id)
    {
        if(Auth::guard('api')->user()->type != 'manager'){
            return Response::json([
                'unauthorized' => 'Access forbiden! Only managers are allowed'
            ], 401);
        }
        if(Auth::guard('api')->user()->id == $id){
            return Response::json([
                'user_cant_delete_himself' => 'You cant delete yourself.'
            ], 422);
        }

        $user = User::findOrFail($id);

        if($user->type == "waiter")
        {
            $meals = $user->meals;
            if($meals->isEmpty()){
                $user->forceDelete();
                return new UserResource($user);
            }

        }elseif ($user->type == "cook")
        {
            $orders = $user->meals;
            if($orders->isEmpty()){
                $user->forceDelete();
                return new UserResource($user);
            }
        }
            $user->delete();
            return new UserResource($user);

    }

    //US6
    public function getCurrentShiftInformation($id){

        $user=User::findOrFail($id);

        if(Auth::guard('api')->user()->id != $user->id){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        return new UserResource($user);
    }

    public function startShift(Request $request, $id){

        $user=User::findOrFail($id);

        if(Auth::guard('api')->user()->id != $user->id){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        $user->shift_active=1;

        $user->last_shift_start=$request->input('date');

        $user->save();

        return new UserResource($user);
    }

    public function endShift(Request $request, $id){

        $user=User::findOrFail($id);

        if(Auth::guard('api')->user()->id != $user->id){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        $user->shift_active=0;

        $user->last_shift_end=$request->input('date');

        $user->save();

        return new UserResource($user);
    }

    public function getCookOrdersList($id){

        $user=User::findOrFail($id);

        $orders = $user->orders;

        if((Auth::guard('api')->user()->id != $user->id) || (Auth::guard('api')->user()->type != 'cook')){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        //get the orders 'confirmed' and 'in preparation'
        $orders = $orders->filter(function ($order) {
            return $order->state == 'confirmed' || $order->state == 'in preparation';
        });

        //Ordenar primeiro as in preparation com data mais antiga (chegaram primeiro à rotunda)
        $orders = $orders->sortBy('start')->sortByDesc('state');

        return OrderResource::collection($orders); 
    }

    public function getMyOrdersWaiter($id){

        $user=User::findOrFail($id);

        if((Auth::guard('api')->user()->id != $user->id) || (Auth::guard('api')->user()->type != 'waiter')){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        $orders = Order::join('meals', 'orders.meal_id', '=', 'meals.id')->where('meals.state', '=', 'active')->where('meals.responsible_waiter_id', '=', $id)->select(
            'orders.id',
            'orders.state',
            'orders.item_id',
            'orders.meal_id',
            'orders.start'

        )->get();

        $orders = $orders->filter(function ($order) {
            return $order->state == 'confirmed' || $order->state == 'pending';
        });

        $orders = $orders->sortBy('state');

        return OrderResource::collection($orders);
    }

    public function getMyPreparedOrdersWaiter($id){

        $user=User::findOrFail($id);

        if((Auth::guard('api')->user()->id != $user->id) || (Auth::guard('api')->user()->type != 'waiter')){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        $orders = Order::join('meals', 'orders.meal_id', '=', 'meals.id')->where('meals.state', '=', 'active')->where('meals.responsible_waiter_id', '=', $id)
        ->where('orders.state', '=', 'prepared')->select(
            'orders.id',
            'orders.state',
            'orders.item_id',
            'orders.meal_id',
            'orders.start'


        )->get();

        $orders = $orders->sortBy('state');

        return OrderResource::collection($orders);
    }

    //Para a store conseguir carregar o user
    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }

    public function registerWorker(Request $request) {

        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username|regex:/^[a-zA-Z0-9]+([._]?[a-zA-Z0-9]+)*$/',
            'type' => Rule::in(['manager', 'cashier', 'cook', 'waiter']),
            'photo' => 'required|image|mimes:jpg,jpeg,png'
        ], ['username.regex' => 'The username must only have letters, numbers, _ and . And can\'t finish with a _ or .',
    ]);

        $user = new User();
       // $user->fill($request->all());
        $user->fill(array_merge($request->all(), ['password' => '123']));
        $user->password = Hash::make($user->password);

        $image = $request->file('photo');
        $path = basename($image->store('profiles', 'public'));
        $user->photo_url = basename($path);

        $user->save();

        event(new Registered($user));
        //Mail::to($user->email)->send(new EmailSender($user->id));

       // return response()->json(new UserResource($user), 201);
        return new UserResource($user);
    }


    public function confirmRegistration(Request $request) {
       /* if ($request->route('id') == $request->user()->getKey() &&
            $request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        } */

        $request->validate([
            'password' => 'required|confirmed|min:3',
            'password_confirmation' => 'required|same:password',
        ]);

        $id = $request->route('id');

        $user = User::findOrFail($id);

        $user->password = Hash::make($request->input('password'));

        $user->email_verified_at = Carbon::now();

        $user->save();

        return new UserResource($user);
    }

    public function blockUser($id) {

        $user = User::findOrFail($id);

        if((Auth::guard('api')->user()->id == $user->id) || (Auth::guard('api')->user()->type != 'manager')){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        if($user->blocked == 1){
            return Response::json([
                'user_already_blocked' => 'User is already blocked!'
            ], 422);
        }

        $user->blocked = 1;
        $user->save();
        return new UserResource($user);
    }


    public function unBlockUser($id) {

        $user = User::findOrFail($id);

        if((Auth::guard('api')->user()->id == $user->id) || (Auth::guard('api')->user()->type != 'manager')){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        if($user->blocked == 0){
            return Response::json([
                'user_already_unblocked' => 'User is already unblocked!'
            ], 422);
        }

        $user->blocked = 0;
        $user->save();
        return new UserResource($user);
    }

    public function getUserByEmail(Request $request) {

        $user = User::where('email', '=', $request->email)->get();
        return new UserResource($user);

    }

}