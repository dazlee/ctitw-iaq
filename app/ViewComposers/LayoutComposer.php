<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;
use App\Device;
use App\Department;

class LayoutComposer
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
            if ($user->hasRole('admin')) {
                $this->devices = Device::all();
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
