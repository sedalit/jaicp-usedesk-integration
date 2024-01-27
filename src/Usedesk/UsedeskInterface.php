<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;

class UsedeskInterface {
    protected $operatorId;
    protected $chatId;
    protected $ticketId;
    protected $platform;
    protected $usedeskChannel;

    function __construct($operatorId, $chatId, $ticketId, $platform, $usedeskChannelId){
        if ($operatorId) {
            $this->operatorId = $operatorId;
        } else {
            $this->operatorId = "";
        }

        $this->chatId = $chatId;
        $this->ticketId = $ticketId;
        $this->platform = $platform;
        $this->setUsedeskChannel($usedeskChannelId);
    }

    public function updateTicket($ticketData) {
        $requestData = new UsedeskApiRequestData($ticketData);
        $request = new UsedeskApiRequest('https://api.usedesk.ru/update/ticket', [], $requestData);

        return $request->make();
    }

    public function switchOperator($targetOperatorId) {
        if ($this->operatorId == $targetOperatorId) return;
        $this->operatorId = $targetOperatorId;

        $requestData = new UsedeskApiRequestData([
            'chat_id' => $this->chatId,
            'user_id' => $this->operatorId 
        ]);
        $request = new UsedeskApiRequest('https://api.usedesk.ru/chat/changeAssignee', [], $requestData);

        return $request->make();
    }

    protected function setUsedeskChannel($channelId) {
        $this->usedeskChannel = "";
    }
}