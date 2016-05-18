<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;
use App\User;
use App\Client;

class ClientComposer
{

    protected $clients = [];
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
                $this->clients = Client::all();
            } else if ($user->hasRole('agent')) {
                $this->clients = Client::where("agent_id", "=", $user->id)->get();
            } else {
                $this->clients = [];
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
        $view->with('clients', $this->clients);
    }
}
