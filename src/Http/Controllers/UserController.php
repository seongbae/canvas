<?php

namespace Seongbae\Canvas\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends CanvasController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // $this->middleware('access.admin');
    } 
    
    public function getUser()
    {
        $user = Auth::user();

        return view('canvas::frontend.user.show')->with('user', $user);
    }

    public function updateProfile(Request $request, $id)
    {
        $user = User::find($id);

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->save();

        return redirect()->back();
    }

    public function updatePassword(Request $request, $id)
    {

        $this->validate($request, [
            'password'=>'required',
            'password_confirm'=>'required|unique:channels,slug|alpha_dash'
        ]);

        $user = User::find($id);

        $user->password = Hash::make($request->get('password'));
        $user->save();

        return redirect()->back();
    }
}
