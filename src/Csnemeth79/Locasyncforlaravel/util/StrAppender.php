<?php

namespace Csnemeth79\Locasyncforlaravel\util;

class StrAppender
{
    private $outStr;

    public function StrAppender()
    {
        $this->outStr = "";
    }

    public function append($str)
    {
        $this->outStr.= $str;
    }

    public function get()
    {
        return $this->outStr;
    }


}