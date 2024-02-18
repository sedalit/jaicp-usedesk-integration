<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers\Message;

use Sedalit\JaicpUsedeskIntegration\Core\Handlers\IRequestHandler;

class MessageFromClient extends BaseHandler implements IRequestHandler {
    function canHandle() {
        $isMessageToBot = $this->ticket->assigneeId() == env()->usedesk('botUserId') || (!$this->ticket->assigneeId() && !$this->ticket->groupId());
        $isMessageFromClient = request()->json()['from'] == "client";
        return $isMessageToBot && $isMessageFromClient;
    }

    function handleRequest($request) {
        $answerFromBot = $this->jaicpInterface->sendMessage();
        $usedeskRequestResult = $this->usedeskInterface->sendMessage($answerFromBot);
        return compact($answerFromBot, $usedeskRequestResult);
    }
}