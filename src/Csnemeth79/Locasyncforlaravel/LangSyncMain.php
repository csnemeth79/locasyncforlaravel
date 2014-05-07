<?php

namespace Csnemeth79\Locasyncforlaravel;

use Csnemeth79\Locasyncforlaravel\service\SyncService;
use Csnemeth79\Locasyncforlaravel\logger\Logger;
use Csnemeth79\Locasyncforlaravel\util\ConfigResolver;


class LangSyncMain
{
    private $logger;
    private $service;
    private $sourceFilesArray;
    private $errors = array();

    function __construct()
    {
        $this->service = new SyncService();
        $this->logger = new Logger();
        $this->collectPriorFiles();
    }


    public function doSync()
    {
        $this->syncFiles();
        $this->eraseDumpFiles();
    }

    public function writeLog()
    {
        if($this->errors &&sizeof($this->errors) > 0) {
            $this->logger->setErrors($this->errors);
            $this->logger->log();
            $this->logger->trace();
        } else {
            $this->logger->filesWereGood();
        }

    }

    private function collectPriorFiles()
    {
        $dir = $this->getDirectory();
        $it = new \RecursiveDirectoryIterator($dir);
        foreach(new \RecursiveIteratorIterator($it) as $file) {
            if(is_file($file) && SyncService::isPriorLanguage($file)) {
                $this->sourceFilesArray[] = $file;
            }
        }
    }

    private function syncFiles()
    {
        if(!is_dir($this->getDirectory()."\\".ConfigResolver::get('prior_language')))
        {
            $this->logger->setErrors(array("No files to syncronize"));
            $this->logger->log();
            $this->logger->trace();
            exit();
        }
        foreach ($this->sourceFilesArray as $value) {
            $this->sync($value);
        }
    }

    private function eraseDumpFiles()
    {
        $this->service->eraseDumpFiles($this->errors);
    }

    private function sync($file)
    {
        $this->service->sync($this->errors, $file);
    }

    private function getDirectory()
    {
        return ConfigResolver::get('main_directory');
    }
}

?>