<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers;
use Sedalit\JaicpUsedeskIntegration\Core\Handlers\Message\MessageFromClient;

class HandlerBoot {
    
    protected $boot = [
        'message' => [
            MessageFromClient::class,
        ],
        'trigger' => []
    ];

    public function boot($ticket, $usedeskClient) {
        $handlers = ['message' => [], 'trigger' => []];
        foreach ($this->boot as $key => $value) {
            foreach ($value as $handler) {
                $handlers[$key][] = new $handler($ticket, $usedeskClient);
            }
        }
        return $handlers;
    }
}