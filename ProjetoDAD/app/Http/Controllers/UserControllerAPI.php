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

class UserControllerAPI extends Controller
{
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
            $request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'username' => 'required|regex:/^[a-zA-Z0-9]+([._]?[a-zA-Z0-9]+)*$/',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png',
            ]   );

            $user = User::findOrFail($id);

            if(Auth::guard('api')->user()->id != $user->id){
                return Response::json([
                    'unauthorized' => 'Access forbiden!'
                ], 401);
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

    //US4 - tem de ter no minimo 3 carateres --> se falhar retorna erro 422
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

        $orders = Order::join('meals', 'orders.meal_id', '=', 'meals.id')->where('meals.state', '=', 'active')->where('meals.responsible_waiter_id', '=', $id)->where('orders.state', '=', 'pending')
            ->orWhere('orders.state', '=', 'confirmed')->where('meals.state', '=', 'active')->where('meals.responsible_waiter_id', '=', $id)->select(
            'orders.id',
            'orders.state',
            'orders.item_id',
            'orders.meal_id',
            'orders.start'

        )->get();

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

    /*public function confirmRegistration(Request $request, $id) {

        $request->validate([
            'password' => 'required|confirmed|min:3',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::findOrFail($id);

        if((Auth::guard('api')->user()->id != $user->id) || (Auth::guard('api')->user()->type != 'cook')){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        $user->password = Hash::make($request->input('password'));

        $user->email_verified_at = Carbon::now();

        $user->save();

        return new UserResource($user);
    } */

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

    public function getCookAllOrdersList($id){

        $user=User::findOrFail($id);

        $orders = $user->orders;

        if((Auth::guard('api')->user()->id != $user->id) || (Auth::guard('api')->user()->type != 'cook')){
            return Response::json([
                'unauthorized' => 'Access forbiden!'
            ], 401);
        }

        $orders = $orders->filter(function ($order) {
            return $order->state == 'confirmed' || $order->state == 'in preparation' || $order->state == 'prepared';
        });

        $orders = $orders->sortBy('start')->sortBy('state');

        return OrderResource::collection($orders);  
    }





}