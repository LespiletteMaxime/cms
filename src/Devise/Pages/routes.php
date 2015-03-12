<?php

/*
|--------------------------------------------------------------------------
| Pages
|--------------------------------------------------------------------------
|
| Loading all pages and creating routes from slugs
| @todo need to implement route caching here for performance boost!
|
*/

if (!function_exists('loadDeviseRoutes'))
{
    function loadDeviseRoutes()
    {
        $filters = \Config::get('devise.permissions');
        $names = array_keys( $filters );

        foreach ($names as $name) {
            Route::filter($name, function($route, $request, $response = null) use ($filters, $name) {
                if(isset($filters[ $name ]['redirect_type'])){
                    $result = DeviseUser::checkConditions($name, true);
                    if($result !== true){
                        if(!\Request::ajax()){
                            return $result;
                        } else {
                            App::abort(403, 'Unauthorized action.');
                        }
                    }
                } else {
                    if(!DeviseUser::checkConditions($name)){
                        App::abort(403, 'Unauthorized action.');
                    }
                }
            });
        }

        $pages = DB::table('dvs_pages')->select('http_verb','slug','route_name','before','after')->get();

        foreach ($pages as $page) {
            $slugRegex = '/([^[\}]+)(?:$|\{)/';

            $page->slug = preg_replace_callback($slugRegex, function ($matches) {
                return strtolower($matches[0]);
            }, $page->slug, -1, $count);

            $verb = $page->http_verb;

            $routeData = array(
                'as' => $page->route_name,
                'uses' => 'Devise\Pages\PageController@show',
            );

            if ($page->before)
            {
                $routeData['before'] = $page->before;
            }

            if ($page->after)
            {
                $routeData['after'] = $page->after;
            }

            Route::$verb($page->slug, $routeData);
        }

    }
}

if(!App::runningInConsole())
{
    try
    {
        loadDeviseRoutes();
    }
    catch (PDOException $e)
    {
        if ($e->getCode() == "1045" || $e->getCode() == "1049" || $e->getCode() == "42S02")
        {
            App::make('Devise\Support\Installer\InstallWizard')->refreshEnvironment();

            if (env('DEVISE_INSTALL') != 'ignore')
            {
                Route::get('/', function() { return Redirect::to("/install/welcome"); });

                // any route non containing string "install"
                Route::any('{any?}', function() { return Redirect::to("/install/welcome"); })
                    ->where('any', '^((?!install).)*$');

                Route::controller('install', 'Devise\Support\Installer\InstallerController');
                return;
            }
        }

        throw $e;
    }
}