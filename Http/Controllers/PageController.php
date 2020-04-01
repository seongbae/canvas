<?php

namespace App\Canvas\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Appstract\Options\Option;
use App\Mail\ContactusSubmitted;
use App\Canvas\Models\Page;

class PageController extends CanvasController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }  
    
    public function getPage($uri=null)
    {
        $paths = explode("/", $uri);
        
        if (count($paths) == 1)
            $page = Page::where('slug', $paths)->first();
        else
        {
            $parent = Page::where('slug', $paths[count($paths)-2])->first();
            $page = Page::where('slug', $paths[count($paths)-1])->where('parent_id', $parent->id)->first();
        }

        if (!$page)
            abort(404);

        return view('page')
            ->with('page', $page)
            ->with('title', $page->meta_title)
            ->with('description', $page->meta_description);
    }


    public function siteMaintenanceMode()
    {
        return view('page.maintenance');
    }

    public function siteComingSoon()
    {
        return tview('page.comingsoon');
    }

    private function addressToArray($emails)
    {
        if( strpos($emails, ',') !== false ) 
            return explode(",",$emails);
        elseif( strpos($emails, ';') !== false ) 
            return explode(";",$emails);
        else
            return $emails;

    }

}
