<?php

namespace Csnemeth79\Locasyncforlaravel\service;

use Csnemeth79\Locasyncforlaravel\util\AbstractFileHandler;
use Csnemeth79\Locasyncforlaravel\fixer\MissingKeyEntity;
use Csnemeth79\Locasyncforlaravel\fixer\ErrorFixer;
use Csnemeth79\Locasyncforlaravel\util\ConfigResolver;


class SyncService extends AbstractFileHandler
{
    private $sourceArray;

    public function sync(&$errors, $file)
    {
        $this->sourceArray = $this->getSourceFile($file);

        $this->validateFileNotExist($errors, $file);
        $this->validateKeys($errors, $file);
    }

    public function eraseDumpFiles(&$errors)
    {
        $dir = ConfigResolver::get('main_directory');
        $it = new \RecursiveDirectoryIterator($dir);
        foreach(new \RecursiveIteratorIterator($it) as $file) {
            if(is_file($file) && !SyncService::isPriorLanguage($file)) {
                if(!SyncService::isPriorFile($file)) {
                    array_push($errors,
                        new Error(ErrorCodes::$dump_file,
                            SyncService::getLangByFilePath($file->getPathName()),
                            $file->getPathName(),
                            "dump file was deleted")
                    );
                    if(ConfigResolver::get('auto_fix')) {
                        unlink($file);
                    }
                }
            }
        }
    }

    private function validateFileNotExist(&$errors, $file)
    {
        foreach (ConfigResolver::get('additional_languages') as $lang) {
            $foreignFileWithPath = $this->getForeignFileWithPath($file, $lang);
            $foreignPath = $this->getForeignPath($file, $lang);

            if(!is_file($foreignFileWithPath)) {
                if(ConfigResolver::get('auto_fix')) {
                    ErrorFixer::writeNewLanguageFile($foreignPath, $file->getFilename(), $this->sourceArray);
                }
                array_push($errors,
                    new Error(ErrorCodes::$file_not_exist,
                        $lang,
                        $file->getFilename(),
                        "whole file generated")
                );
            }
        }
    }

    private function validateKeys(&$errors, $file)
    {
        foreach (ConfigResolver::get('additional_languages') as $lang) {
            $foreignArray = $this->getForeignFileAsArray($file, $lang);
            if($foreignArray)
            {
                $missesEntity = new MissingKeyEntity($this->getForeignFileWithPath($file, $lang));

                foreach ($this->sourceArray as $key=>$value) {
                    if(!array_key_exists($key, $foreignArray))
                    {
                        $missesEntity->add($key, $value);
                        array_push($errors,
                            new Error(ErrorCodes::$key_not_exist,
                                $lang,
                                $file->getFilename(),
                                "key generated: [".$key."]"));
                    }
                }

                if(ConfigResolver::get('auto_fix') && !$missesEntity->isEmpty()) {
                    ErrorFixer::writeMissingKeys($missesEntity);
                }
            }
        }
    }

    public static function isPriorFile($file)
    {
        $file = ConfigResolver::get('main_directory')."\\".ConfigResolver::get('prior_language')."\\".$file->getFileName();
        return is_file($file);
    }

    public static function getLangByFilePath($filePath)
    {
        $chopPos = strlen(ConfigResolver::get('main_directory'))+1;
        $fileWithLang = substr($filePath, $chopPos, strlen($filePath));
        return strstr($fileWithLang, '\\', true);
    }

    public static function isPriorLanguage($file)
    {
        if (strpos($file, ConfigResolver::get('prior_language').'\\') !== FALSE)
        {
            return true;
        }
        return false;
    }

}