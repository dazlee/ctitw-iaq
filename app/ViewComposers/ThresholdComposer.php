<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Auth;
use App\Threshold;

class ThresholdComposer
{

    protected $threshold = [];
    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        $this->threshold = Threshold::first();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('threshold', $this->threshold);
    }
}
