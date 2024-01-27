<?php

namespace Sedalit\JaicpUsedeskIntegration\Jaicp;

class JaicpInterface {
    protected const SEND_MESSAGE_METHOD = "/chatadapter/chatapi/";
    public $botAnswer;
    protected $apiRequest;
    protected $apiRequestData;

    function __construct($clientId, $query, $data){
        $this->apiRequestData = new JaicpApiRequestData($clientId, $query, $data);

        $chatApiToken = "";
        $hostUrl = "";
        $this->apiRequest = new JaicpApiRequest($hostUrl . self::SEND_MESSAGE_METHOD . $chatApiToken, $this->apiRequestData);
    }

    public function sendMessage() {
        $this->botAnswer = $this->apiRequest->make();
        return $this->botAnswer;
    }
}