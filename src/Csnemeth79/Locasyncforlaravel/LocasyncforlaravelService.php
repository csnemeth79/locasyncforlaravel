<?php

namespace Csnemeth79\Locasyncforlaravel;

use Csnemeth79\Locasyncforlaravel\util\ConfigResolver;

class LocasyncforlaravelService {

    public function test() {
        $sync = new LangSyncMain();
        $sync->doSync();
        $sync->writeLog();
    }

    public function LocasyncforlaravelService() {
        echo "dwq";
    }

}

?>