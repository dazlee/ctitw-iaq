<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\Threshold;

class SettingController extends Controller
{
    //
    public function index () {
        $threshold = Threshold::first();

        return view("settings", array(
            'threshold' => $threshold,
        ));
    }

    public function update (Request $request) {
        $this->validate($request, [
            'co2' => 'required|numeric|min:0',
            'rh' => 'required|numeric|min:0',
            'temp' => 'required|numeric|min:0',
        ]);

        $threshold = Threshold::first();
        $threshold->co2 = (float)$request->get("co2");
        $threshold->temp = (float)$request->get("temp");
        $threshold->rh = (float)$request->get("rh");
        $threshold->save();
        return Redirect::back();
    }
}
