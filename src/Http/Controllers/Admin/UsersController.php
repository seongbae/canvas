<?php

namespace Seongbae\Canvas\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Debugbar;
use Cache;
use Auth;
use Yajra\Datatables\Datatables;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Alert;
use Seongbae\Canvas\DataTables\UsersDataTable;
use Intervention\Image\Facades\Image;
use Seongbae\Canvas\Http\Controllers\CanvasController;

class UsersController extends CanvasController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }  

    public function getAll()
    {
        $users = User::all();

        return "<pre>".$users->toJson(JSON_PRETTY_PRINT)."</pre>";
    }

    public function getUser($id)
    {
        $user = User::find($id);

        return $user;
    }

    public function index(UsersDataTable $datatable)
    {
        return $datatable->render('canvas::admin.users.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function create()
    {   
        $roles = Role::all()->sortBy('order');

        return view('canvas::admin.users.create')
                ->with('roles', $roles);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|unique:users,email',
        ]);

        $user = new User;
        $user->name = $request->get('name');
        $user->email =$request->get('email');
        $user->password = Hash::make($request->get('password'));

        if ($request->file('file'))
        {
            $file = $request->file->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $name = uniqid('img_').'.'. $extension; 
            $storagePath = public_path('users/'.$name);
            
            Image::make($request->file('file'))
                ->fit(400, 400)
                ->save($storagePath);

            $user->photo_url = asset('img/users/'.$name);
        }
        
        $user->save();

        if ($request->get('role'))
        {
            $user->syncRoles($request->get('role'));
        }

        // Send notification if checked
        if ($request->get('send_email'))
        {
            $args = [];

            if ($request->get('include_password'))
                $args = array('password'=>$request->get('password'));

            event(new \App\Events\NewUserCreated($user, $args));
        }

        flash()->success('User successfully created', 'Success');

        return redirect()->route('admin.users.index');
    }

    public function edit($id)
    {
        $user = User::find($id);

        $roles = Role::all()->sortBy('order');

        return view('canvas::admin.users.edit')
                    ->with('user', $user)
                    ->with('roles', $roles);
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$id,
        ]);

        $user = User::find($id);

        if (trim($request->get('name')))
            $user->name = trim($request->get('name'));

        if (trim($request->get('email')))
            $user->email = trim($request->get('email'));

        if ($request->get('password') && $request->get('password_confirm') && $request->get('password') != '')
            $user->password = Hash::make(trim($request->get('password')));

        if ($request->get('role'))
        {
            $user->syncRoles($request->get('role'));
        }

        if ($request->file('file'))
        {
            $file = $request->file->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $name = uniqid('img_').'.'. $extension; 
            $storagePath = public_path('img/users/'.$name); //'public/profiles';
            //$storedFile = $request->file->storeAs($storagePath, $name);
            // if (!file_exists($storagePath)) {
            //     mkdir($storagePath, 666, true);
            // }

            Image::make($request->file('file'))
                ->fit(400, 400)
                ->save($storagePath);

            $user->photo_url = asset('img/users/'.$name);
        }

        $user->save();

        flash()->success('User successfully updated', 'Success');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $user = User::findorFail($id);
        $user->delete();

        flash()->success('User successfully deleted', 'Success');

        return redirect()->route('admin.users.index');
    }

}
