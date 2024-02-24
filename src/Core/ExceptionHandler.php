<?php

namespace Sedalit\JaicpUsedeskIntegration\Core;

use ErrorException;

class ExceptionHandler {
    protected $integration;

    public function __construct(Integration $integration) {
        $this->integration = $integration;
        error_reporting(-1);
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        if (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    public function handleException(\Throwable $e)
    {
        Logger::log('errors', ["Request:" => request()->json(), "Error:" => $e]);
    }
}