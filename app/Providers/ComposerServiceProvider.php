<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'layouts.app', 'App\ViewComposers\LayoutComposer'
        );

        view()->composer(
            'partials.tables.agent-account-table', 'App\ViewComposers\AgentComposer'
        );

        view()->composer(
            'partials.tables.client-account-table', 'App\ViewComposers\ClientComposer'
        );

        view()->composer(
            'partials.tables.department-account-table', 'App\ViewComposers\DepartmentComposer'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
