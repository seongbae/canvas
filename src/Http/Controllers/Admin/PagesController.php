<?php

namespace Seongbae\Canvas\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Log;
use Seongbae\Canvas\DataTables\PagesDataTable;
use Seongbae\Canvas\Http\Controllers\CanvasController;
use Seongbae\Canvas\Models\Page;

class PagesController extends CanvasController
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
    public function index(PagesDataTable $datatable)
    {
        return $datatable->render('canvas::admin.pages.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function create()
    {   
        $pages = Page::all();

        return view('canvas::admin.pages.create')
                ->with('pages', $pages);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required',
            //'slug'=>'required|unique:pages,slug|alpha_dash',
            'body' => 'required'
        ]);

        $page = new Page;
        $page->name = $request->get('name');
        $page->slug = slugify($request->get('name'));
        $page->body = $request->get('body');
        $page->meta_title = $request->get('meta_title');
        $page->meta_description = $request->get('meta_description');
        $page->user_id = Auth::id();
        $page->parent_id = $request->get('parent_id');
        $page->save();

        flash()->success('Page successfully added', 'Success');

        return redirect()->route('admin.pages.index');
    }

    public function edit($id)
    {
        $page = Page::find($id);
        $pages = Page::where('id', '!=', $page->id)->get();


        return view('canvas::admin.pages.edit')
            ->with('page', $page)
            ->with('pages', $pages);
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name'=>'required',
            'slug'=>'required|unique:pages,slug,'.$id.'|alpha_dash',
            'body' => 'required'
        ]);

        $page = Page::find($id);

        $page->name = $request->get('name');

        $page->slug = $request->get('slug');
            

        $page->body = $request->get('body');
        $page->user_id = Auth::id();
        $page->meta_title = $request->get('meta_title');
        $page->meta_description = $request->get('meta_description');
        $page->save();

        flash()->success('Page successfully updated', 'Success');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $page = Page::findorFail($id);

        // $settings = setting();
            
        // if (setting('home_page') && setting('home_page') == $page->id)
        //     $settings->forget('home_page');
        // else if (count(Page::all()) == 0)
        //     $settings->forget('home_page');

        $page->delete();

        flash()->success('Page deleted', 'Success');

        return redirect()->route('admin.pages.index');
    }
}
