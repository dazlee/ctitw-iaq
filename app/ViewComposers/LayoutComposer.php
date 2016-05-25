<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Auth;
use App\Device;
use App\Department;

class LayoutComposer
{

    protected $showSide = false;
    protected $devices = [];
    protected $statsUrl = "/stats";
    protected $historyUrl = "/history";
    protected $allUrl = "/all";
    protected $dashboardBaseUrl = "/dashboard";
    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        $user = Auth::user();

        if ($user) {
            $id = Route::current()->getParameter('id');
            if ($user->hasRole('admin')) {
                switch (Route::currentRouteName()) {
                    case '_client_device':
                    case '_client_stats':
                    case '_client_history':
                    case '_client_all':
                        $this->showSide = true;
                        $this->dashboardBaseUrl = "/client" . "/" . $id . "/dashboard";
                        $this->devices = Device::where('client_id', '=', $id)->get();
                        $this->statsUrl   = "/client" . "/" . $id . "/stats";
                        $this->historyUrl = "/client" . "/" . $id . "/history";
                        $this->allUrl     = "/client" . "/" . $id . "/all";
                        break;
                }
            } else if ($user->hasRole('client')) {
                $this->showSide = true;

                $this->devices = Device::where('client_id', '=', $user->id)->get();
            } else if ($user->hasRole('department')) {
                $this->showSide = true;

                $department = Department::where('user_id', '=', $user->id)->first();
                $this->devices = Device::where('client_id', '=', $department->client_id)->get();
            }
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('showSide', $this->showSide);
        $view->with('devices', $this->devices);
        $view->with('statsUrl', $this->statsUrl);
        $view->with('historyUrl', $this->historyUrl);
        $view->with('allUrl', $this->allUrl);
        $view->with('dashboardBaseUrl', $this->dashboardBaseUrl);
    }
}
