<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;
use App\Client;
use App\User;
use App\Threshold;

class SettingComposer
{

    protected $threshold = [];
    public function __construct()
    {
        $user = Auth::user();

        if ($user) {
            if ($user->hasRole('admin')) {
                $this->threshold = Threshold::first();
            } else if ($user->hasRole('client')) {
                $threshold = Threshold::where('user_id', '=', $user->id)->first();
                if (!isset($threshold)) {
                    $threshold = Threshold::first();
                }
                $this->threshold = $threshold;
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
        $view->with('threshold', $this->threshold);
    }
}
