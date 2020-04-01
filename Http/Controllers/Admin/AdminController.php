<?php

namespace App\Canvas\Http\Controllers\Admin;

use Artisan;
use Auth;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;
use Appstract\Options\Option;
use App\Canvas\DataTables\ActivitiesDataTable;
use Spatie\Searchable\Search;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use App\Helpers\AdminHelper;
use App\Canvas\Http\Controllers\CanvasController;

class AdminController extends CanvasController
{

    use UploadTrait;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }  

    public function showHome()
    {
        return view('canvas::admin.home');
    }

    public function showActivityLogs(ActivitiesDataTable $datatable)
    {
        return $datatable->render('canvas::admin.logs.activity');
    }

    public function showSystemLogs()
    {
        return view('canvas::admin.logs.system');
    }

    public function search(Request $request)
    {
        $searchResults = (new Search())
            ->registerModel(User::class, 'name')
            ->registerModel(Event::class, 'name')
            ->perform($request->input('query'));

        return view('canvas::admin.search', compact('searchResults'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showSettings()
    {
        $modules = [];
        $dir = new \DirectoryIterator(app_path('Modules'));
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $modules[] = $fileinfo->getFilename();
            }
        }

        $enabledModules = [];
        $moduleOptionGroups = [];
        
        if (option('modules') != null)
        {
            $modulesArray = json_decode(option('modules'), true);

            foreach ($modulesArray as $key=>$value)
            {
                if ($value == 2)
                {
                    app('config')->set('custom', require app_path('Modules/'.$key.'/module.php'));

                    $enabledModules[] = $key;

                    $moduleOptionGroups[$key] = config('custom.options');

                    if (config('custom.options'))
                        foreach(config('custom.options') as $option)
                            $options[$option['slug']] = option($option['slug']);
                }
            }
        }
        
        return view('canvas::admin.settings')
                ->with('modules', $modules)
                ->with('enabledModules', $enabledModules)
                ->with('moduleOptionGroups', $moduleOptionGroups);
    }

    public function saveSettings(Request $request)
    {
        option(['site_name' => $request->get('site_name') ? $request->get('site_name') : '' ]);
        option(['site_description' => $request->get('site_description') ? $request->get('site_description') : '' ]);
        option(['from_name' => $request->get('from_name') ? $request->get('from_name') : '' ]);
        option(['from_email' => $request->get('from_email') ? $request->get('from_email') : '' ]);
        option(['notification_email' => $request->get('notification_email') ? $request->get('notification_email') : '' ]);
        option(['google_analytics_id' => $request->get('google_analytics_id') ? $request->get('google_analytics_id') : '' ]);


        $modulesArray = json_decode(option('modules'), true);
        $modules = [];

        if ($modulesArray != null)
        foreach ($modulesArray as $key=>$value)
        {
            if ($request->get('modules'))
                if (in_array($key, $request->get('modules')))
                {
                    app('config')->set('custom', require app_path('Modules/'.$key.'/module.php'));

                    // Publish files
                    if ($value == 1)
                    {
                        \App::register('App\Modules\\'.$key.'\\'.config('custom.service_provider'));
                        Log::info('publishing files for: '.'App\Modules\\'.$key.'\\'.config('custom.service_provider'));
                        Artisan::call('vendor:publish', array('--provider' => 'App\Modules\\'.$key.'\\'.config('custom.service_provider'), '--force' => true));
                    }
                    

                    if (config('custom.options'))
                    {
                        foreach (config('custom.options') as $option)
                        {
                            if ($request->get($option['slug']))
                                option([$option['slug'] =>  $request->get($option['slug'])]);
                        }
                    }

                    $modules[$key] = 2;
                }
                else
                    $modules[$key] = 1;
            else
                $modules[$key] = 1;
        }

        option(['modules' => json_encode($modules) ]);

        //option(['theme' => $request->get('theme') ? $request->get('theme') : '' ]);
        
        if ($request->get('maintenance_mode') == 1)
            Artisan::call('down');
        else if (option('maintenance_mode') == 1)
            Artisan::call('up');

        option(['maintenance_mode' => $request->get('maintenance_mode') == 1 ? 1 : 0 ]);

        option(['business_name' => $request->get('business_name') ? $request->get('business_name') : '' ]);
        option(['business_email' => $request->get('business_email') ? $request->get('business_email') : '' ]);
        option(['business_phone' => $request->get('business_phone') ? $request->get('business_phone') : '' ]);
        option(['business_address' => $request->get('business_address') ? $request->get('business_address') : '' ]);

        // Advanced 
        option(['resource_rename' => $request->get('resource_rename') ? $request->get('resource_rename') : '' ]);

        return redirect()->back();
    }

    public function installModule($module)
    {
        app('config')->set('custom', require app_path('Modules/'.$module.'/module.php'));

        // Run migrations
        Artisan::call('migrate', array('--path' => 'app/Modules/'.$module.'/Migrations', '--force' => true));

        $issues = array();

        if (config('custom.options'))
        {
            foreach (config('custom.options') as $option)
            {
                option([$option['slug'] => array_key_exists('default', $option) ? $option['default'] : '']);
            }
        }

        $permissions = config('custom.permissions');

        foreach ($permissions as $permission)
        {
            try {
                
                $permission = Permission::create(['name'=>$permission]);

            }
            catch (PermissionAlreadyExists $e) {
                $issues[] = 'Permission already exists: '.$permission;
            }
        }

        if (count($issues) == 0)
        {
            $modulesArray = json_decode(option('modules'), true);
            $modules = [];
            $moduleFound = false;

            if ($modulesArray != null)
                foreach ($modulesArray as $key=>$value)
                {
                    if ($key == $module && $value == 0)
                    {
                        $modules[$key] = 1;
                        $moduleFound = true;
                    }
                    else
                        $modules[$key] = $value;
                }

            if (!$moduleFound)
                $modules[$module] = 1;

            option(['modules' => json_encode($modules) ]);
        }


        if (count($issues) > 0)
            flash('Could not install the module.'.json_encode($issues),'alert-danger');
        else
            flash()->success('Successfully installed the module.');

        return redirect()->route('admin.settings');

    }

    public function uninstallModule($module)
    {
        app('config')->set('custom', require app_path('Modules/'.$module.'/module.php'));

        // rollback migrations
        Artisan::call('migrate:rollback', array('--path' => 'app/Modules/'.$module.'/Migrations', '--force' => true));

        $issues = array();

        if (config('custom.options'))
        {
            foreach (config('custom.options') as $option)
            {
                if (option_exists($option['slug']))
                    option()->remove($option['slug']);
            }
        }

        $permissions = config('custom.permissions');

        if ($permissions && count($permissions) > 0)
        {
            foreach ($permissions as $permission)
            {
                try {
                    
                    $permission = Permission::findByName($permission);

                    if ($permission)
                        $permission->delete();

                }
                catch (PermissionDoesNotExist $e) {
                    $issues[] = 'Permission does not exist: '.$permission;
                }

            }
        }
        
        $modulesArray = json_decode(option('modules'), true);
        $modules = [];

        foreach ($modulesArray as $key=>$value)
        {
            if ($key == $module && $value >= 1)
                $modules[$key] = 0;
            else
                $modules[$key] = $value;
        }

        option(['modules' => json_encode($modules) ]);

        flash()->success('Module un-installed successfully.');

        return redirect()->back();
    }

    public function showAccount()
    {
        $user = Auth::user();

        return view('canvas::admin.account')->with('user', $user);
    
    }

}
