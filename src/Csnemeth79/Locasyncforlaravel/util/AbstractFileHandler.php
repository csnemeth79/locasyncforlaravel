<?php

namespace Csnemeth79\Locasyncforlaravel\util;

abstract class AbstractFileHandler
{
    protected function getSourceFile($file)
    {
        return require_once $file->getPathname();
    }

    protected function getForeignFileAsArray($file, $lang)
    {
        $foreignPathname = str_replace(ConfigResolver::get('prior_language')."\\", $lang."\\", $file->getPathname());
        if(is_file($foreignPathname)) {
            return require_once $foreignPathname;
        }
    }

    protected function getForeignFileWithPath($file, $lang)
    {
        return str_replace(ConfigResolver::get('prior_language')."\\", $lang."\\", $file->getPathname());
    }

    protected function getForeignPath($file, $lang)
    {
        return str_replace(ConfigResolver::get('prior_language'), $lang, $file->getPath());
    }
}