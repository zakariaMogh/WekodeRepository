<?php

namespace Wekode\Repository;

//use App\Providers\SettingServiceProvider;

use Illuminate\Support\ServiceProvider;
use Wekode\Repository\Commands\ContractMakeCommand;
use Wekode\Repository\Commands\RepositoryMakeCommand;

class RepositorySetupServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() :void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Check if telr transaction table is migrated
//        if (! class_exists('CreateSettingsTable')) {
//            $this->publishes([
//                __DIR__.'/migrations/create_settings_table.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_settings_table.php",
//            ], 'migrations');
//        }
        $this->publishes([
            __DIR__.'/Traits/FindAbleTrait.stub' => base_path('App/Traits/FindAbleTrait.php'),
            __DIR__.'/Traits/UploadAble.stub' => base_path('App/Traits/UploadAble.php'),
            __DIR__.'/Providers/RepositoryServiceProvider.stub' => base_path('App/Providers/RepositoryServiceProvider.php'),
            __DIR__.'/Base/CrudContract.stub' => base_path('App/Contracts/Base/CrudContract.php'),
            __DIR__.'/Base/BaseRepositories.stub' => base_path('App/Repositories/BaseRepositories.php'),
        ]);

//        $this->app->register(SettingServiceProvider::class);
        if ($this->app->runningInConsole()) {
            $this->commands([
                ContractMakeCommand::class,
                RepositoryMakeCommand::class,
            ]);
        }
    }
}
