<?php

namespace Sedalit\JaicpUsedeskIntegration\Core;
use Sedalit\JaicpUsedeskIntegration\Core\Handlers\Handler;
use Sedalit\JaicpUsedeskIntegration\Usedesk\Client;
use Sedalit\JaicpUsedeskIntegration\Usedesk\Ticket;

class Integration {
    protected $ticket;
    protected $usedeskClient;
    protected $handler;

    function __construct() {
        $request = request();

        $this->usedeskClient = new Client($request->client());
        $this->ticket = new Ticket($request->ticket());
        $this->handler = new Handler($this->usedeskClient, $this->ticket);
    }

    public function handle() {
        return $this->handler->handle();
    }
}