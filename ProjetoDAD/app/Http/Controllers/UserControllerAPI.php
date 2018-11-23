<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\User as UserResource;
use App\User;

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

    public function changePassword(Request $request){


        
        /*$validatedData=$request->validate([
            'old_password'=>'required',
            'password'=>'required|confirmed|min:3|different:old_password',
            'password_confirmation'=>'required|same:password',
        ]);*/

        //$user=User::findOrFail($id);

        //echo($request->user());

        //return new UserResource($user);

        //$user = User::findOrFail();

        

        //valida pasword e retorna response em json se não for valida

        //se for faz update do utilizador -> da password e retorna o resource.


        /*
        if ($request->has('cancel')) {
            return redirect()->route('home');
        }
        $validatedData=$request->validate([
            'old_password'=>'required',
            'password'=>'required|confirmed|min:3|different:old_password',
            'password_confirmation'=>'required|same:password',
        ], [
        'old_password.required' => 'You must enter your current password',
        'password.required' => 'You must enter a new password',
        'password.different' => 'The new password must be different from the current password',
        'password.min' => 'The new password must have at least 3 characters',
        'password_confirmation.required' => 'You must enter the confirmation password',
        'password_confirmation.same' => 'The confirmation password must be equal to new password',
        ]);
        if (!(Hash::check($request->input('old_password'), Auth::user()->password))) {
            return redirect()->route('me.password')->withErrors(['old_password' => 'Please enter the correct current password']);
        }
            
        $user_id=Auth::id();
        $user=User::findOrFail($user_id);
        $user->password=Hash::make($request->input('password'));
        $user->save();
        return redirect()->route('home')->with('success', 'Your password has been updated');
        */
    }

    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }
}
