<?php

namespace Csnemeth79\Locasyncforlaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Locasyncforlaravel extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'locasyncforlaravel';
    }

}