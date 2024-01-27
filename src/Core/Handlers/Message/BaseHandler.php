<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers\Message;

abstract class BaseHanlder {
    protected $usedeskInterface;
    protected $jaicpInterface;

    function __construct($usedeskInterface, $jaicpInterface) {
        $this->usedeskInterface = $usedeskInterface;
        $this->jaicpInterface = $jaicpInterface;
    }
}