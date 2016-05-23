<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Input;
use Auth;
use App\User;
use App\Department;

class DepartmentComposer
{

    protected $departments = [];
    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        $user = Auth::user();
        $client_id = Input::get('client_id');

        if ($user) {
            if ($user->hasRole('admin')) {
                $this->departments = empty($client_id) ? Department::all() : Department::where('client_id', '=', $client_id)->get();
            } else if ($user->hasRole('client')) {
                $this->departments = Department::where('client_id', '=', $user->id)->get();
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
        $view->with('departments', $this->departments);
    }
}
