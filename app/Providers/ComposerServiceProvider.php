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
            'stats', 'App\ViewComposers\StatsComposer'
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

        view()->composer(
            'partials.tables.file-table', 'App\ViewComposers\FileComposer'
        );

        view()->composer(
            'settings', 'App\ViewComposers\SettingComposer'
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
