<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Device;

class LayoutComposer
{

    protected $departments;
    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // [TODO] should load all departments here
        $this->departments = Device::all();
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
