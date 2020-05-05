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
use Seongbae\Canvas\Http\Controllers\CanvasController;
use File;
use Storage;
use Seongbae\Canvas\Traits\UploadTrait;
use Seongbae\Canvas\Events\NewUserCreated;

class UsersController extends CanvasController
{

    use UploadTrait;

    const USER_IMG_STORAGE_PATH = 'app/public/users';
    const USER_IMG_ASSET_PATH = 'storage/users';

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
            $user->photo_url = $this->saveUserImage($request->file('file'), 'users');
        
        $user->save();

        if ($request->get('role'))
            $user->syncRoles($request->get('role'));

        // Send notification if checked
        if ($request->get('send_email'))
        {
            $args = [];

            if ($request->get('include_password'))
                $args = array('password'=>$request->get('password'));

            event(new NewUserCreated($user, $args));
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
            $user->syncRoles($request->get('role'));
        
        if ($request->file('file'))
            $user->photo_url = $this->saveUserImage($request->file('file'), 'users');
        
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
