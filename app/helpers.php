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
