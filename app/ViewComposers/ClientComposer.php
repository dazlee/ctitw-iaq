<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Input;
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
        $agent_id = Input::get('agent_id');

        if ($user) {
            if ($user->hasRole('admin')) {
                $this->clients = empty($agent_id) ? Client::all() : Client::where("agent_id", "=", $agent_id)->get();
            } else if ($user->hasRole('agent')) {
                $this->clients = Client::where("agent_id", "=", $user->id)->get();
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
