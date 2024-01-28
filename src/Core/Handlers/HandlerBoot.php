<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers;
use Sedalit\JaicpUsedeskIntegration\Core\Handlers\Message\MessageFromClient;

class HandlerBoot {
    
    protected $boot = [
        'message' => [
            MessageFromClient::class,
        ],
    ];

    public function boot() {
        return $this->boot;
    }
}