<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Auth;
use App\User;
use App\Client;
use App\Department;
use App\Device;

class MultipleStatsComposer
{

    protected $devices = [];
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
            $clientId = Route::current()->getParameter('id');
            if ($user->hasRole('admin')) {
                $this->devices = Device::where('client_id', '=', $clientId)->get();
            } else if ($user->hasRole('client')) {
                $this->devices = Device::where('client_id', '=', $user->id)->get();
            } else if ($user->hasRole('department')) {
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
        $view->with('devices', $this->devices);
    }
}
