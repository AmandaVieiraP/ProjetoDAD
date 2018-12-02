<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\User as UserResource;
use Hash;
use App\StoreUserRequest;
use App\User;
use Response;

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
        //fazer validações

       // var_dump($request->photo);
       //  echo($request);
       // dd($request);


       // if($request->name != null)
        //{
            $request->validate([
                'name'=>'required|min:3|regex:/(^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÒÖÚÇÑ0-9 ]+$)+/',
                'username'=>'required|min:3|regex:/(^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÒÖÚÇÑ0-9 ]+$)+/',
                'image'=>'nullable|image|mimes:png,jpeg,jpg',

            ]);
      //  }


        $user = User::findOrFail($id);

        $image = $request->file('image');
        $path = basename($image->store('profiles','public'));
        //$user->photo_url = basename($path);
        //$user->save();
        //$user->update($request->all());
        return new UserResource($user);

    }


    /*
     * $user = $request->user();
        $user->fill($validatedData);
        if (!$request->has('phone')) {
        $user->phone=null;
        }
        if ($request->hasFile('profile_photo')) {
        $image=$request->file('profile_photo');
        $path = basename($image->store('profiles', 'public'));
        $user->profile_photo=basename($path);
        }
     */



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

        return new UserResource($user);
    }

    public function startShift(Request $request, $id){

        $user=User::findOrFail($id);

        $user->shift_active=1;

        $user->last_shift_start=$request->input('date');

        $user->save();

        return new UserResource($user);
    }

    public function endShift(Request $request, $id){

        $user=User::findOrFail($id);

        $user->shift_active=0;

        $user->last_shift_end=$request->input('date');

        $user->save();

        return new UserResource($user);
    }

    //Para a store conseguir carregar o user
    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }
}
