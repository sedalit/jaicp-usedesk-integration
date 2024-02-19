<?php

namespace Sedalit\JaicpUsedeskIntegration\Jaicp;

class JaicpInterface {
    protected const SEND_MESSAGE_METHOD = "/chatadapter/chatapi/";
    public $botAnswer;
    protected $apiRequest;
    protected $apiRequestData;

    function __construct($clientId, $query, $data){
        $this->apiRequestData = new JaicpApiRequestData($clientId, $query, $data);
    }

    public function setData(array $data) {
        $this->apiRequestData->setData($data);
    }

    public function setEvent(string $event) {
        $this->apiRequestData->setEvent($event);
    }

    public function sendMessage() {
        $chatApiToken = env()->tokens('chatApiToken');
        $hostUrl = env()->jaicp('host');
        
        $this->apiRequest = new JaicpApiRequest($hostUrl . self::SEND_MESSAGE_METHOD . $chatApiToken, $this->apiRequestData);
        $this->botAnswer = $this->apiRequest->make();
        return $this->botAnswer;
    }
}