<?php

namespace Csnemeth79\Locasyncforlaravel\service;

use Csnemeth79\Locasyncforlaravel\util\ConfigResolver;

class Error
{
    var $fileName;
    var $language;
    var $errorCode;
    var $operation = "did nothing";

    public function __construct($errorCode, $language, $file, $operation=null)
    {
        $this->errorCode = $errorCode;
        $this->language = $language;
        $this->fileName = $file;

        if(ConfigResolver::get('auto_fix')) {
            $this->operation = $operation;
        }
    }


}