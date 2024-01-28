<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers\Message;

use Sedalit\JaicpUsedeskIntegration\Core\Handlers\IRequestHandler;

class MessageFromClient extends BaseHandler implements IRequestHandler {
    function canHandle() {
        $isBotAssigned = $this->ticket->assigneeId == env()->usedesk('botUserId');
        $isOperatorGroupAssigned = $this->isOperatorGroupAssigned();
        return (request()->chatId() != false && request()->json()['from'] == "client") && ($isBotAssigned == true && $isOperatorGroupAssigned != true);
    }

    function handleRequest($request) {
        $answerFromBot = $this->jaicpInterface->sendMessage();
        $usedeskRequestResult = $this->usedeskInterface->sendMessage($answerFromBot);

        return compact($answerFromBot, $usedeskRequestResult);
    }

    protected function isOperatorGroupAssigned() {
        return $this->ticket->group == env()->operatorGroups('defaultOperatorGroupID') || $this->ticket->group == env()->operatorGroups($this->ticket->channel());
    }
}