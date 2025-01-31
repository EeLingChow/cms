<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

use App\Models\Event;

function cdn($asset)
{
    if (!Config::get('app.cdn'))
        return asset($asset);

    return Config::get('app.cdn') . $asset;
}

function svgicon($path)
{
    return cdn('assets/media/icons/svg/' . urlencode($path));
}

function is_current_master($routes)
{
    $route = Route::currentRouteName();
    return in_array($route, $routes) ? 'kt-menu__item--open' : '';
}

function is_current_route($route)
{
    $currentRoute = Route::currentRouteName();
    return $currentRoute == $route ? 'kt-menu__item--active' : '';
}

function build_filter($filters)
{
    $i = 0;
    $html = '<div class="form-group">';
    foreach ($filters as $r) {
        $i++;
        $html .= view('admins._layouts.form.filter-row', compact('r'))->render();

        if ($i % 6 == 0) {
            $html .= '</div><div class="form-group">';
        }
    }

    $html .= '</div>';
    return $html;
}

function build_form($form, $data = [])
{
    $labelWidth = 3;
    $inputWidth = 9;

    $html = '';

    foreach ($form as $key => $settings) {
        $html .= view('admins._layouts.form.form-group', compact('labelWidth', 'inputWidth', 'key', 'settings', 'data'))->render();
    }

    return $html;
}

function rearrange_form($form, $keys)
{
    $parsed = [];
    foreach ($keys as $k) {
        if (isset($form[$k])) {
            $parsed[$k] = $form[$k];
        }
    }

    return $parsed;
}

function reverse_geocode($lat, $long)
{
    $url = "https://api.bigdatacloud.net/data/reverse-geocode-client?latitude={$lat}&longitude={$long}&localityLanguage=en";
    $data = file_get_contents($url);

    $json = json_decode($data, true);

    return [
        'country' => $json['countryName'],
        'state' => $json['locality'],
        'loc' => implode(' ', [$json['locality'], $json['countryName']]) . '*',
    ];
}

function admin()
{
    return auth('admin')->user();
}

function convert_gmt($datetime, $gmt)
{
    if ($gmt == 7) {
        $dt = new \DateTime($datetime, new \DateTimeZone('+0800'));
        $dt->setTimezone(new \DateTimeZone('+0700'));

        return $dt->format('Y-m-d H:i:s');
    }

    return $datetime;
}

function api_token()
{
    return session()->get('api_token');
}

if (!function_exists('add_module_routes')) {
    function add_module_routes($moduleKey, $options, $extraRoutes = null)
    {
        $defaults = [
            'prefix' => '',
            'middleware' => ["module:{$moduleKey}"],
            'key' => $moduleKey,
            'name' => '',
            'controller' => 'Admin\\' . ucfirst($moduleKey) . 'Controller',
        ];

        $options = array_merge($defaults, $options);

        Route::group(['prefix' => $options['prefix'], 'middleware' => $options['middleware']], function () use ($options, $extraRoutes) {
            $module = $options['key'];
            $moduleName = $options['name'];
            $controller = $options['controller'];

            //Listing
            Route::get('/', ['uses' => "{$controller}@index"])->name("{$moduleName}.list")
                ->middleware("permission:{$module},read");

            //Create
            Route::get('/create', ['uses' => "{$controller}@create"])->name("{$moduleName}.create")
                ->middleware("permission:{$module},create");
            //Editing
            Route::get('/edit/{id}', ['uses' => "{$controller}@edit"])->name("{$moduleName}.edit")
                ->where('id', '\d+')
                ->middleware("permission:{$module},update");

            if ($extraRoutes) {
                $extraRoutes();
            }
        });
    }
}

if (!function_exists('add_api_module_routes')) {
    function add_api_module_routes($moduleKey, $options, $extraRoutes = null)
    {
        $defaults = [
            'prefix' => '',
            'middleware' => [],
            'key' => $moduleKey,
            'name' => '',
            'exclude' => [],
            'controller' => 'Api\\' . ucfirst($moduleKey) . 'Controller',
        ];

        $options = array_merge($defaults, $options);

        Route::group(['prefix' => $options['prefix']], function () use ($options, $extraRoutes) {
            $module = $options['key'];
            $moduleName = $options['name'];
            $controller = $options['controller'];

            //Listing
            if (!in_array('listing', $options['exclude'])) {
                Route::get('/', ['uses' => "{$controller}@index"])
                    ->name("api.{$moduleName}.list")
                    ->middleware("permission:{$module},read");

                //show
                Route::get('/{id}', ['uses' => "{$controller}@show"])
                    ->name("api.{$moduleName}.show")
                    ->where('id', '\d+')
                    ->middleware("permission:{$module},read");
            }

            //Create
            if (!in_array('create', $options['exclude'])) {
                Route::post('/create', ['uses' => "{$controller}@store"])
                    ->name("api.{$moduleName}.store")
                    ->middleware("permission:{$module},create", "audit:{$module},create");
            }

            //Update
            if (!in_array('update', $options['exclude'])) {
                Route::post('/update/{id}', ['uses' => "{$controller}@update"])
                    ->name("api.{$moduleName}.update")
                    ->where('id', '\d+')
                    ->middleware("permission:{$module},update", "audit:{$module},update");
            }

            //Delete
            if (!in_array('delete', $options['exclude'])) {
                Route::post('/delete/{id}', ['uses' => "{$controller}@delete"])
                    ->name("api.{$moduleName}.delete")
                    ->where('id', '\d+')
                    ->middleware("permission:{$module},delete", "audit:{$module},delete");
            }

            if ($extraRoutes) {
                $extraRoutes();
            }
        });
    }
}
