<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;

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
        $this->departments = [
            [
                'name'  => '部門1',
                'device_id' => 1,
            ],
            [
                'name'  => '部門2',
                'device_id' => 2,
            ],
            [
                'name'  => '部門3',
                'device_id' => 3,
            ],
            [
                'name'  => '部門4',
                'device_id' => 4,
            ],
            [
                'name'  => '部門5',
                'device_id' => 5,
            ],
            [
                'name'  => '部門6',
                'device_id' => 6,
            ],
            [
                'name'  => '部門7',
                'device_id' => 7,
            ],
        ];
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
