<?php

namespace Minjemin\Flysystem\MinjeminStorage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class MinjeminStorageServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('minjemin', function ($app, $config) {
            $client = new MinjeminClient($config['api_url'], $config['username'], $config['password']);
            return new Filesystem(new MinjeminStorageAdapter($client));
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}