<?php

namespace App\Helpers;

use Illuminate\Http\Request;

trait RequestParser
{
    public function parseSearchQueryRequest(Request $request, &$validator = null)
    {
        $data = $request->only(['filters', 'query', 'limit', 'offset', 'order']);

        $validator = $this->validator->make($data, [
            'filters' => 'json',
            'limit' => 'integer',
            'offset' => 'integer',
            'order' => 'json'
        ]);

        if ($validator->fails()) {
            return null;
        }

        $data = [
            'filters' => $data['filters'] ? json_decode($data['filters'], true) : [],
            'limit' => $data['limit'] ? $data['limit'] : 0,
            'offset' => $data['offset'] ? $data['offset'] : 0,
            'order' => $data['order'] ? $data['order'] : ['key' => 'distance', 'direction' => 'asc'],
        ];

        if ($validator->fails()) {
            return null;
        }

        return $data;
    }

    public function parseQueryRequest(Request $request)
    {
        $keys = ['filters', 'limit', 'orders', 'pagination'];
        $data = $request->only($keys);

        foreach ($keys as $k) {
            if (!array_key_exists($k, $data)) {
                $data[$k] = null;
            }
        }

        $return = [
            'limit'     => $data['limit'] ? $data['limit'] : 1000,
            'pagination' => $data['pagination'] ?: [
                'page' => 1,
                'perpage' => 1000,
            ],
        ];

        foreach (['filters' => 'filters', 'orders' => 'orders'] as $k => $name) {
            if ($data[$k]) {
                if (is_array($data[$k])) {
                    $return[$name] = $data[$k];
                } else if (json_decode($data[$k], true) !== false) {
                    $return[$name] = json_decode($data[$k], true);
                }
            } else {
                if ($k == 'orders') {
                    $return[$name] = ['created_at' => 'desc'];
                } else {
                    $return[$name] = [];
                }
            }

            $parsed = [];
            foreach ($return[$name] as $k => $v) {
                $parsed[str_replace('-', '.', $k)] = $v;
            }

            $return[$name] = $parsed;
        }

        return $return;
    }
}
