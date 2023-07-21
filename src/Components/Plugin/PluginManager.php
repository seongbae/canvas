<?php
namespace Canvas\App\Components\Plugin;

use Canvas\App\Exceptions\PermissionExists;
use Canvas\App\Models\Menu;
use Canvas\App\Models\MenuItem;
use Canvas\App\Models\Permission;
use Canvas\App\Models\Plugin;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Support\Facades\DB;

class PluginManager
{
    private $app;

    /**
     * @var PluginManager
     */
    private static $instance = null;

    /**
     * @var string
     */
    protected $pluginDirectory;

    /**
     * @var array
     */
    public $plugins = [];

    /**
     * @var array
     */
    protected $classMap = [];

    /**
     * @var PluginExtender
     */
    protected $pluginExtender;

    protected $pluginAdminMenus = [];

    /**
     * PluginManager constructor.
     *
     * @param $app
     */
    public function __construct($app)
    {
        $this->app             = $app;
        $this->pluginDirectory = base_path('plugins'); //config('canvas.plugin_path');
        $this->pluginExtender  = new PluginExtender($this, $app);

        $this->bootPlugins();
        $this->pluginExtender->extendAll();

        $this->registerClassLoader();
    }

    /**
     * Registers plugin autoloader.
     */
    private function registerClassLoader()
    {
        spl_autoload_register([new ClassLoader($this), 'loadClass'], true, true);
    }

    /**
     * @param $app
     * @return PluginManager
     */
    public static function getInstance($app)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($app);
        }

        return self::$instance;
    }

    protected function bootPlugins()
    {
        foreach (Finder::create()->in($this->pluginDirectory)->directories()->depth(0) as $dir) {

            /** @var SplFileInfo $dir */
            $directoryName = $dir->getBasename();

            $pluginClass = $this->getPluginClassNameFromDirectory($directoryName);

            if (!class_exists($pluginClass)) {
                //Log::debug('Plugin ' . $directoryName . ' needs a ' . $directoryName . 'Plugin class.');
                continue;
            }

            try {
                $plugin = $this->app->makeWith($pluginClass, [$this->app]);
            } catch (\ReflectionException $e) {
                //Log::debug('Plugin ' . $directoryName . ' could not be booted: "' . $e->getMessage() . '"');
                continue;
            }

            if (!($plugin instanceof Plugin)) {
                //Log::debug('Plugin ' . $directoryName . ' must extend the Plugin Base Class');
                continue;
            }

            $this->plugins[$plugin->name] = $plugin;

            if (config('app.migration_run')) {
                if (plugin_enabled($directoryName)) {
                    $plugin->boot();

                    $this->app['config']->set('plugin', require $this->getPluginDirectory() . DIRECTORY_SEPARATOR . $directoryName. '/plugin.php');

                    if (config('plugin.admin_menus'))
                        $this->pluginAdminMenus[] = config('plugin.admin_menus');

                }
            }

        }
    }

    public function installPlugin($plugin)
    {
        $issues = array();

        app('config')->set('plugin', require config('canvas.plugin_path').DIRECTORY_SEPARATOR.$plugin.DIRECTORY_SEPARATOR.'plugin.php');

        if (config('plugin.options'))
        {
            foreach (config('plugin.options') as $option)
                option([$option['slug'] => array_key_exists('default', $option) ? $option['default'] : '']);
        }

        $permissions = config('plugin.permissions');

        if ($permissions) {
            foreach ($permissions as $permission)
            {
                if (Permission::where(['name'=>$permission])->first())
                    throw new PermissionExists($permission);
                else
                    Permission::create(['name'=>$permission]);
            }
        }

        $pluginUserMenus = config('plugin.user_menus');
        $userMenu = Menu::where('slug','user-menu')->first();

        if ($pluginUserMenus && $userMenu) {
            foreach ($pluginUserMenus as $menu)
            {
                $menuItem = MenuItem::where('menu_id', $userMenu->id)->where('link',$menu['url'])->first();

                if (!$menuItem)
                    MenuItem::create(['label'=>$menu['name'],'link'=>$menu['url'], 'menu_id'=>$userMenu->id]);
            }
        }

        if (count($issues) == 0)
        {
            Artisan::call(
                'migrate',
                array(
                    '--path' => 'plugins'.DIRECTORY_SEPARATOR.$plugin.DIRECTORY_SEPARATOR.'migrations',
                    '--force'=> true
                )
            );

            $pluginsArray = unserialize(option('plugins'));
            $plugins = [];
            $pluginFound = false;

            if ($pluginsArray != null)
                foreach ($pluginsArray as $key=>$value)
                {
                    if ($key == $plugin && $value == 0)
                    {
                        $plugins[$key] = 1;
                        $pluginFound = true;
                    }
                    else
                        $plugins[$key] = $value;
                }

            if (!$pluginFound)
                $plugins[$plugin] = 1;

            option(['plugins' => serialize($plugins) ]);

            return true;
        }
        else
            return false;
    }

    public function uninstallPlugin($plugin)
    {
        $issues = array();

        app('config')->set('plugin', require config('canvas.plugin_path').DIRECTORY_SEPARATOR.$plugin.DIRECTORY_SEPARATOR.'plugin.php');

        if (config('plugin.options'))
            foreach (config('plugin.options') as $option)
                if (option_exists($option['slug']))
                    option()->remove($option['slug']);

        $permissions = config('plugin.permissions');

        if ($permissions && count($permissions) > 0)
        {
            foreach ($permissions as $permission)
            {
                try {
                    $permission = Permission::where('name', $permission)->first();

                    if ($permission)
                        $permission->delete();

                }
                catch (PermissionDoesNotExist $e) {
                    $issues[] = 'Permission does not exist: '.$permission;
                }
            }
        }

        $pluginUserMenus = config('plugin.user_menus');
        $userMenu = Menu::where('slug','user-menu')->first();

        if ($pluginUserMenus && $userMenu) {
            foreach ($pluginUserMenus as $menu)
            {
                $menuItem = MenuItem::where('menu_id', $userMenu->id)->where('link',$menu['url'])->first();

                if ($menuItem)
                    $menuItem->delete();

            }
        }

        $pluginsArray = unserialize(option('plugins'));
        $plugins = [];

        foreach ($pluginsArray as $key=>$value)
        {
            if ($key == $plugin && $value >= 1)
                $plugins[$key] = 0;
            else
                $plugins[$key] = $value;
        }

        option(['plugins' => serialize($plugins) ]);

        if (count($issues) == 0)
        {
            Artisan::call(
                'migrate:rollback',
                array(
                    '--path' => 'canvas'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$plugin.DIRECTORY_SEPARATOR.'migrations',
                    '--force'=> true
                )
            );

            return true;
        }
        else
            return false;
    }

    public function uninstallAllPlugins()
    {
        $pluginsArray = unserialize(option('plugins'));

        if ($pluginsArray != null)
            foreach ($pluginsArray as $key=>$value)
            {
                $this->uninstallPlugin($key);
            }
    }

    public function updatePluginStatus($plugins=[])
    {
        $pluginsArray = unserialize(option('plugins'));
        $updatedPlugins = [];

        if ($pluginsArray != null) {
            foreach ($pluginsArray as $plugin=>$status)  {
                if (in_array($plugin, $plugins))
                    $updatedPlugins[$plugin] = 2;
                else
                    $updatedPlugins[$plugin] = 1;
            }
        }

        option(['plugins' => serialize($updatedPlugins) ]);
    }

    public function getPluginAdminMenus()
    {
        return $this->pluginAdminMenus;
    }

    /**
     * @param $directory
     * @return string
     */
    protected function getPluginClassNameFromDirectory($directory)
    {
        return "Canvas\\Plugins\\${directory}\\${directory}Plugin";
    }

    /**
     * @return array
     */
    public function getClassMap()
    {
        return $this->classMap;
    }

    /**
     * @param array $classMap
     * @return $this
     */
    public function setClassMap($classMap)
    {
        $this->classMap = $classMap;

        return $this;
    }

    /**
     * @param $classNamespace
     * @param $storagePath
     */
    public function addClassMapping($classNamespace, $storagePath)
    {
        $this->classMap[$classNamespace] = $storagePath;
    }

    /**
     * @return array
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @return string
     */
    public function getPluginDirectory()
    {
        return $this->pluginDirectory;
    }
}
