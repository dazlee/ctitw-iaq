<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

use Auth;
use App\User;
use App\Client;

class StatsFileComposer
{
    private $fileBasePath = '/files';

    protected $downloads = [];
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
            if ($user->hasRole('client')) {
                $deviceAccount = $user->client->device_account;
                $path = base_path() . $this->fileBasePath . '/' . $deviceAccount;
                if(File::exists($path)) {
                    $years = File::directories($path);
                    $years = array_reverse($years);
                    foreach ($years as $year) {
                        $quarters = File::directories($year);
                        $quarters = array_reverse($quarters);
                        foreach ($quarters as $quarter) {
                            $quarterSegs = explode('/', $quarter);
                            $len = count($quarterSegs);
                            $Q = $quarterSegs[$len-1];
                            $Y = $quarterSegs[$len-2];
                            $D = $quarterSegs[$len-3];
                            $dir = [
                                'name' => $Y . '-' . $Q,
                                'path' => $D . '/' . $Y . '/' . $Q,
                            ];
                            array_push($this->downloads, $dir);
                        }
                    }
                }
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
        $view->with('downloads', $this->downloads);
    }
}
