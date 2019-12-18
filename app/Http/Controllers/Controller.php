<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function error($code = 500)
    {
        switch ($code) {
            case 400:
                $status      = 202;
                $description = 'The request body can’t be pasred as valid data.';
                break;
            case 401:
                $status      = 202;
                $description = 'Unauthorized';
                break;
            case 10001:
                $status      = 202;
                $description = 'Category Unsupported';
                break;
            case 10002:
                $status      = 202;
                $description = 'Sent fail';
                break;
            default:
                $status      = 500;
                $description = 'Internal Server Error.';
        }

        return response([
            'success' => false,
            'error'   => [
                'status'      => $status,
                'code'        => $code,
                'description' => $description,
            ],
        ]);
    }

    protected function success($data)
    {
        if (! isset($data['data'])) {
            $data = ['data' => $data];
        }

        return response(array_merge(['success' => true], $data));
    }
}
