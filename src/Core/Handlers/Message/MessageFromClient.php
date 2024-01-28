<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers\Message;

use Sedalit\JaicpUsedeskIntegration\Core\Config;
use Sedalit\JaicpUsedeskIntegration\Core\Handlers\IRequestHandler;

class MessageFromClient extends BaseHandler implements IRequestHandler {
    function canHandle() {
        $isBotAssigned = $this->ticket->assigneeId == Config::usedesk('botUserId');
        $isOperatorGroupAssigned = $this->isOperatorGroupAssigned();
        return (request()->chatId() != false && request()->json()['from'] == "client") && ($isBotAssigned == true && $isOperatorGroupAssigned != true);
    }

    function handleRequest($request) {
        return 'Procceed';
    }

    protected function isOperatorGroupAssigned() {
        return $this->ticket->group == Config::operatorGroups('defaultOperatorGroupID') || $this->ticket->group == Config::operatorGroups($this->ticket->channel());
    }
}