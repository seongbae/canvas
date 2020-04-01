<?php

namespace App\Canvas\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Debugbar;
use Cache;
use Auth;
use App\User;
use Yajra\Datatables\Datatables;
use App\Badge;
use Spatie\Permission\Models\Role;
use App\Channel;
use App\Canvas\DataTables\RolesDataTable;
use App\Canvas\Http\Controllers\CanvasController;

class RolesController extends CanvasController
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

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index(RolesDataTable $datatable)
    {
        return $datatable->render('canvas::admin.roles.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function create()
    {   
        return view('canvas::admin.roles.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required'
        ]);

        $role = new Role;

        if (trim($request->get('name')))
            $role->name = trim($request->get('name'));
        
        $role->guard_name = 'web';
        //$role->order = 1;
        $role->save();

        if ($request->get('rolepermission'))
        {
            $role->permissions()->sync($request->get('rolepermission'));
            $role->save();
        }

        flash()->success('Role successfully added', 'Success');

        return redirect()->route('admin.roles.index');
    }

    public function show()
    {

    }

    public function edit($id)
    {
        $role = Role::find($id);

        return view('canvas::admin.roles.edit')->with('role', $role);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required'
        ]);

        $role = Role::find($id);

        if (trim($request->get('name')))
            $role->name = trim($request->get('name'));

        if ($role)
        {
            $role->permissions()->sync($request->rolepermission);
            $role->save();
        }

        flash()->success('Role successfully updated', 'Success');


        return redirect()->back();
    }

    public function destroy($id)
    {
        $role = Role::findorFail($id);
        $role->delete();

        flash()->success('Role successfully deleted', 'Success');

        return redirect()->route('admin.roles.index');
    }

}
