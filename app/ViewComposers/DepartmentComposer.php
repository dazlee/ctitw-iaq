<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
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

        if ($user) {
            if ($user->hasRole('admin')) {
                $this->departments = Department::all();
            } else if ($user->hasRole('client')) {
                $this->departments = Department::where('client_id', '=', $user->id)->get();
            } else  {
                $this->departments = [];
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
