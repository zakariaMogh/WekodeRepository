<?php

namespace Wekode\Repository;


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
        $this->publishes([
            __DIR__.'/Traits/FindAbleTrait.stub' => base_path('App/Traits/FindAbleTrait.php'),
            __DIR__.'/Traits/UploadAble.stub' => base_path('App/Traits/UploadAble.php'),
            __DIR__.'/Providers/RepositoryServiceProvider.stub' => base_path('App/Providers/RepositoryServiceProvider.php'),
            __DIR__.'/Base/CrudContract.stub' => base_path('App/Contracts/Base/CrudContract.php'),
            __DIR__.'/Base/BaseRepositories.stub' => base_path('App/Repositories/BaseRepositories.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ContractMakeCommand::class,
                RepositoryMakeCommand::class,
            ]);
        }
    }
}
