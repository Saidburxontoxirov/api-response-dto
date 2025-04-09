<?php

namespace Burxon\ApiResponseDTO;

use Burxon\ApiResponseDTO\Commands\CreateResponseDTOCommand;
use Illuminate\Support\ServiceProvider;

class ApiResponseDTOServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateResponseDTOCommand::class,
                // ... other commands
            ]);
        }
    }

    public function register()
    {
        //
    }
}
