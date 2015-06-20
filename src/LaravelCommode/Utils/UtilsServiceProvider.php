<?php

namespace LaravelCommode\Utils;

use LaravelCommode\SilentService\SilentService;
use LaravelCommode\Utils\Meta\Localization\MetaManager;

class UtilsServiceProvider extends SilentService
{
    const PROVIDES_META_MANAGER = 'laravel-commode.utils.meta';

    /**
     * This method will be triggered instead
     * of original ServiceProvider::register().
     * @return mixed
     */
    public function registering()
    {
        $this->app->singleton(self::PROVIDES_META_MANAGER, function () {
            return new MetaManager();
        });
    }

    /**
     * This method will be triggered instead
     * when application's booting event is fired.
     * @return mixed
     */
    public function launching()
    {
    }
}
