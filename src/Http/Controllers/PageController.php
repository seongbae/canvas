<?php

namespace Seongbae\Canvas\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Appstract\Options\Option;
use App\Mail\ContactusSubmitted;
use Seongbae\Canvas\Models\Page;

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
        $permalinks = array_flip(json_decode(option('resource_rename'), true));
        
        if (count($paths) == 1)
        {
            if (array_key_exists($paths[0], $permalinks))
            {
                $route = app('router')->getRoutes()->match(app('request')->create($permalinks[$paths[0]]));
                return \App::call($route->action['controller']); 
            }    
            
            $page = Page::where('slug', $paths[0])->first();

        }
        else
        {
            if (array_key_exists($paths[0], $permalinks))
            {
                $route = app('router')->getRoutes()->match(app('request')->create($permalinks[$paths[0]].'/'.$paths[1]));
                return \App::call($route->action['controller'], [$paths[1]]);
            }
            
            $parent = Page::where('slug', $paths[count($paths)-2])->first();

            if($parent)
                $page = Page::where('slug', $paths[count($paths)-1])->where('parent_id', $parent->id)->first();
            else
                $page = null;
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
