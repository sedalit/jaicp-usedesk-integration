<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers\Message;
use Sedalit\JaicpUsedeskIntegration\Jaicp\JaicpInterface;
use Sedalit\JaicpUsedeskIntegration\Usedesk\UsedeskInterface;

abstract class BaseHandler {
    protected $usedeskInterface;
    protected $jaicpInterface;
    protected $ticket;
    protected $usedeskClient;

    protected function boot($ticket, $usedeskClient) {
        $this->ticket = $ticket;
        $this->usedeskClient = $usedeskClient;

        $this->usedeskInterface = new UsedeskInterface($ticket->assigneeId, request()->chatId(), $ticket->id, request()->platform(), $ticket->channelId);
        $this->jaicpInterface = new JaicpInterface($ticket->id, $ticket->message, []);
    }
}