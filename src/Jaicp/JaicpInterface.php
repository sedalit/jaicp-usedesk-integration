<?php

namespace Sedalit\JaicpUsedeskIntegration\Jaicp;

class JaicpInterface {
    protected const SEND_MESSAGE_METHOD = "/chatadapter/chatapi/";

    /**
     * @var mixed Ответ, полученный от бота
     */
    public $botAnswer;

    /**
     * @var JaicpApiRequest Сформированнный запрос к API JAICP
     */
    protected $apiRequest;

    /**
     * @var JaicpApiRequestData Объект, хранящий данные для отправки к боту
     */
    protected $apiRequestData;

    function __construct(int $clientId, string $query, array $data)
    {
        $this->apiRequestData = new JaicpApiRequestData($clientId, $query, $data);
    }

    public function setData(array $data) 
    {
        $this->apiRequestData->setData($data);
    }

    public function setEvent(string $event) 
    {
        $this->apiRequestData->setEvent($event);
    }

    /**
     * Функция отправки сообщения в бота
     * @return array Ответ, полученный от бота
     */
    public function sendMessage() : array
    {
        $chatApiToken = env()->tokens('chatApiToken');
        $hostUrl = env()->jaicp('host');
        
        $this->apiRequest = new JaicpApiRequest($hostUrl . self::SEND_MESSAGE_METHOD . $chatApiToken, $this->apiRequestData);
        $this->botAnswer = $this->apiRequest->make();
        return $this->botAnswer;
    }
}