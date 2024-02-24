<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers\Message;

use Sedalit\JaicpUsedeskIntegration\Core\Handlers\IRequestHandler;
use Sedalit\JaicpUsedeskIntegration\Http\Request;

class MessageFromClient extends BaseHandler implements IRequestHandler {

    function canHandle() : bool
    {
        $isMessageToBot = $this->ticket->assigneeId() == env()->usedesk('botUserId') || (!$this->ticket->assigneeId() && !$this->ticket->groupId());
        $isMessageFromClient = request()->json()['from'] == "client";

        return $isMessageToBot && $isMessageFromClient;
    }
 
    function handleRequest(Request $request)
    {
        if ($this->ticket->hasFiles()) {
            $this->jaicpInterface->setData($this->ticket->files());
            $this->jaicpInterface->setEvent('fileEvent');
        }
        
        $answerFromBot = $this->jaicpInterface->sendMessage();
        $usedeskRequestResult = $this->usedeskInterface->sendMessage($answerFromBot);
        return compact($answerFromBot, $usedeskRequestResult);
    }
}