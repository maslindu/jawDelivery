<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function responseSuccess($message, $data, $code)
    {
        $return = [
            "status"     => 'success',
            "code"       => $code,
            "message"    => $message,
            "data"       => $data
        ];

        return response($return, $code);
    }

    function responseError($message, $error, $code)
    {
        $return = [
            "status"    => 'error',
            "code"      => $code,
            "message"   => $message,
            "error"     => $error
        ];

        return response($return, $code);
    }
}

