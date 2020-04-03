<?php

use Illuminate\Contracts\View\Factory as ViewFactory;

if ( !function_exists('greeting') )
{
	function greeting($name){
		return 'Howdy ' . $name;
	}
}

if (! function_exists('themeView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function themeView($view = null, $data = [], $mergeData = [])
    {
        $factory = app(ViewFactory::class);

        $theme = option('theme');

        if (func_num_args() === 0) {
            return $factory;
        }

        if (view()->exists('themes.'.$theme.'.'.$view))
            $view = 'themes.'.$theme.'.'.$view;

        return $factory->make($view, $data, $mergeData);
    }
}

if (! function_exists('themePath')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function themePath($path)
    {
        $theme = option('theme');

        if (view()->exists('themes.'.$theme.'.'.$path))
          $path = 'themes.'.$theme.'.'.$path;

        return $path;
    }
}

if (! function_exists('slugify')) {
    function slugify($text)
    {
      // replace non letter or digits by -
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);

      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);

      // trim
      $text = trim($text, '-');

      // remove duplicate -
      $text = preg_replace('~-+~', '-', $text);

      // lowercase
      $text = strtolower($text);

      if (empty($text)) {
        return 'n-a';
      }

      return $text;
    }
}

if (! function_exists('formatDate')) {
    function format_date($d, $format=null) 
    {
        $date=date_create($d);

        if ($date == null)
            return "n/a";

        if ($format != null)
            return date_format($date,$format);
        else
        {

            return date_format($date,"M d, Y");
        }
    }
}

if (! function_exists('moduleInstalled')) {
    function moduleInstalled($name)
    {
        $modulesArray = json_decode(option('modules'), true);
      
        if ($modulesArray != null)  
          foreach ($modulesArray as $key=>$value)
          {
              if ($key == $name && $value >= 1)
                  return true;
          }

        return false;
    }
}

if (! function_exists('canvas_route')) {
    function canvas_route($name, $parameters = [], $absolute = true)
    {
        $renames = json_decode(option('resource_rename'), true);
        $url = app('url')->route($name, $parameters, $absolute);
        $parsedURL = parse_url($url);
        
        $renameFound = false;

        foreach (array_keys($renames) as $resource) {
            if (strpos($parsedURL['path'], $resource) !== FALSE) {
                $renameFound = true;
            }
        }

        if (count($renames) > 0 && $renameFound) 
          $url = $parsedURL['scheme'] . "://" . $parsedURL['host'] . str_replace(array_keys($renames), $renames, $parsedURL['path']);
        
        return $url;
    }
}