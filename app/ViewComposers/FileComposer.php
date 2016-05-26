<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;
use App\Client;
use App\User;
use App\UserFile;

class FileComposer
{

    protected $files = [];
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
                $this->files = UserFile::all();
            } else if ($user->hasRole('client')) {
                $this->files = UserFile::where("user_id", "=", $user->id)->get();
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
        $view->with('files', $this->files);
    }
}
