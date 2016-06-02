<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Auth;
use App\User;
use App\Client;
use App\Department;

class StatsComposer
{

    protected $deviceAccount = "";
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
            $client = [];
            if ($user->hasRole('admin')) {
                $client_id = Route::getCurrentRoute()->getParameter('id');
                $client = Client::where("user_id", "=", $client_id)->first();
            } else if ($user->hasRole('client')) {
                $client = Client::where("user_id", "=", $user->id)->first();
            } else if ($user->hasRole('department')) {
                $client = Department::where("user_id", "=", $user->id)->first()->client;
            }

            $this->deviceAccount = $client->device_account;
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
        $view->with('deviceAccount', $this->deviceAccount);
    }
}
