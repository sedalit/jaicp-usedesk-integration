<?php

namespace Sedalit\JaicpUsedeskIntegration\Core;

class Logger {
    static function log($dir, $data){
        $log = date('Y-m-d H:i:s') . ' ' . print_r($data, true);
        $fileName = date('Y-m-d') . '.txt';
        file_put_contents('logs/' . $dir . '/' . $fileName.'.txt', $log . PHP_EOL, FILE_APPEND);
    }
}