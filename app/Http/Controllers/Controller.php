<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        // [TODO] should load departments data from DB
        View::composer('layouts.app', function ($view){
            $departments = [
                [
                    'name'  => '部門1',
                    'device_id' => 1,
                ],
                [
                    'name'  => '部門2',
                    'device_id' => 2,
                ],
                [
                    'name'  => '部門3',
                    'device_id' => 3,
                ],
                [
                    'name'  => '部門4',
                    'device_id' => 4,
                ],
                [
                    'name'  => '部門5',
                    'device_id' => 5,
                ],
                [
                    'name'  => '部門6',
                    'device_id' => 6,
                ],
            ];
            $view->with('departments', $departments);
        });
    }
}
