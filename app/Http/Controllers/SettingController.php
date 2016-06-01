<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Auth;
use App\Http\Requests;
use App\Threshold;

class SettingController extends Controller
{
    //
    public function index () {
        $user = Auth::user();
        $updateUrl = "/settings";
        if ($user && $user->hasRole('client')) {
            $updateUrl = "/client/". $user->id ."/settings";
        }
        return view("settings", [
            'updateUrl' => $updateUrl
        ]);
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

    public function updateClientSetting (Request $request, $client_id) {
        $this->validate($request, [
            'co2' => 'required|numeric|min:0',
            'rh' => 'required|numeric|min:0',
            'temp' => 'required|numeric|min:0',
        ]);

        $threshold = Threshold::where("user_id", "=", $client_id)->first();
        if (isset($threshold)) {
            $threshold->co2 = (float)$request->get("co2");
            $threshold->temp = (float)$request->get("temp");
            $threshold->rh = (float)$request->get("rh");
            $threshold->save();
        } else {
            Threshold::create([
                'co2'   => (float)$request->get("co2"),
                'temp'  => (float)$request->get("temp"),
                'rh'    => (float)$request->get("rh"),
                'user_id' => ''.$client_id,
            ]);
        }

        return Redirect::back();
    }
}
