<?php

namespace Seongbae\Canvas\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Seongbae\Canvas\Models\Media;
use Seongbae\Canvas\DataTables\MediasDataTable;
use Auth;
use Seongbae\Canvas\Traits\UploadTrait;
use Illuminate\Support\Str;
use Seongbae\Canvas\Http\Controllers\CanvasController;

class MediaController extends CanvasController
{

    use UploadTrait;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MediasDataTable $datatable)
    {
        $medias = Media::all();

        return $datatable->render('canvas::admin.media.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('canvas::admin.media.create')
                ->with('media', null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $media = new Media;
        $media->name = $request->get('name');
        $media->description = $request->get('description');
        
        if ($request->has('file_url')) {
            // Get image file
            $file = $request->file('file_url');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($request->input('name')).'_'.time();
            // Define folder path
            $folder = '/media/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = '/storage'.$folder . $name. '.' . $file->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($file, $folder, 'public', $name);
            // Set user profile image path in database to filePath
            $media->path = $name. '.' . $file->getClientOriginalExtension();
        }

        $media->type = 'image';
        $media->user_id = Auth::id();

        if ($request->get('tags')) {
            $media->syncTags(explode(",", $request->get('tags')));
        }

        $media->save();

        flash()->success('Item saved');

        return redirect()->route('admin.media.index');
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
    public function edit(Media $medium)
    {
        return view('canvas::admin.media.edit')->with('media', $medium);
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
        $media = Media::find($id); //where('id', $id)->update($request->all());

        $media->name = $request->get('name');
        $media->description = $request->get('description');
        
        if ($request->has('file_url')) {
            // Get image file
            $file = $request->file('file_url');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($request->input('name')).'_'.time();
            // Define folder path
            $folder = '/media/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = '/storage'.$folder . $name. '.' . $file->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($file, $folder, 'public', $name);
            // Set user profile image path in database to filePath
            $media->path = $name. '.' . $file->getClientOriginalExtension();
        }

        if ($request->get('tags')) {
            $media->syncTags(explode(",", $request->get('tags')));
        }
        
        $media->save();

        flash()->success('Item saved');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $media = Media::find($id);
        $media->delete();

        return redirect()->route('admin.media.index');
    }

    public function loadModal($id)
    {
        // write your process if any
        return view('canvas::admin.media.idex');
    }
}
