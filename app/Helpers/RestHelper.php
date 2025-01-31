<?php

namespace App\Helpers;

use Illuminate\Http\Request;

trait RestHelper
{
    protected function noContent()
    {
        return response()->noContent();
    }

    protected function responseWithMessage($status, $message)
    {
        return $this->response($status, null, $message);
    }

    protected function response($status, $data, $message = '', $meta = null)
    {
        $return = [
            'status' => $status,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $return['data'] = $data;
        }

        if ($meta) {
            $return['meta'] = $meta;
        }

        return response()->json($return, $status);
    }

    protected function paginateResponse($status, $data, $message = '')
    {
        $json = json_decode($data->response()->content(), true);

        $return = [
            'status' => $status,
            'message' => $message,
            'data' => $json['data'],
            'links' => $json['links'],
            'pagination' => $json['meta'],
            'meta' => [
                'page' => $json['meta']['current_page'],
                'pages' => $json['meta']['last_page'],
                'perpage' => $json['meta']['per_page'],
                'total' => $json['meta']['total'],
                'sort' => 'desc',
                'field' => 'meta.created',
            ],
        ];

        return response()->json($return, $status);
    }

    protected function error($status, $message, $errors = array())
    {
        $return = [
            'status' => $status,
            'message' => $message,
            'errors' => $errors,
        ];

        return response()->json($return, $status);
    }

    protected function getErrors($messageBag)
    {
        $errors = [];
        foreach ($messageBag as $key => $messages) {
            $errors[$key] = [];
            foreach ($messages as $msg) {
                //$errors[$key][] = str_replace([':message', ':key'], [$msg, $key], ':message');
                $errors[$key][] = str_replace([':message', ':key'], [$msg, $key], ':message');
            }
        }
        return $errors;
    }
}
