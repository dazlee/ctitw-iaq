<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
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
        // $user = Auth::user();

        // if ($user) {
        //     if ($user->hasRole('admin')) {
        //         $clients = Client::all();
        //
        //         foreach ($clients as $client) {
        //             $this->clients[] = $client->user;
        //         }
        //     } else if ($user->hasRole('client')) {
        //         $clients = Client::where('client_id', '=', $user->id)->get();
        //
        //         foreach ($clients as $client) {
        //             $this->clients[] = $client->user;
        //         }
        //     } else if ($user->hasRole('department')) {
        //         $this->clients[] = $user;
        //     }
        // }


        // [TODO] should use code above
        $this->clients = User::all();
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
