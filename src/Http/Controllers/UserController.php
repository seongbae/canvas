<?php

namespace Seongbae\Canvas\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Seongbae\Canvas\Traits\UploadTrait;

class UserController extends CanvasController
{
    use UploadTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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

        if ($request->file('file'))
        {
            $user->photo_url = URL::to("/storage/".$this->uploadOne($request->file('file'), 'users', 'public'));
            $user->save();
        }

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
