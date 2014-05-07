<?php

namespace Csnemeth79\Locasyncforlaravel\util;

use Whoops\Example\Exception;

class ConfigResolver {

    public static function get($prop) {
        $str = \Config::get('locasyncforlaravel::'.$prop);
        if($str == null) {
            throw new Exception("Error resolving config property.");
        }
        return $str;
    }

} 