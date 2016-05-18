<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;
use App\Device;
use App\Department;

class LayoutComposer
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
                $departments = Department::all();

                foreach ($departments as $department) {
                    $this->departments[] = $department->user;
                }
            } else if ($user->hasRole('client')) {
                $departments = Department::where('client_id', '=', $user->id)->get();

                foreach ($departments as $department) {
                    $this->departments[] = $department->user;
                }
            } else if ($user->hasRole('department')) {
                $this->departments[] = $user;
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
