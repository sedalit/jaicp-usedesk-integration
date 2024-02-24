<?php

namespace Sedalit\JaicpUsedeskIntegration\Jaicp;

class JaicpApiRequestData {

    /**
     * @var int ID клиента
     */
    protected $clientId;

    /**
     * @var string Текущее сообщение клиента к боту
     */
    protected $query;

    /**
     * @var array Массив дополнительных данных, которые нужно передать боту
     */
    protected $data = [];

    /**
     * @var string Событие в боте, которое должно вызвать сообщение клиента
     */
    protected $event;

    function __construct(int $clientId, string $query, array $data = []) 
    {
        $this->clientId = $clientId;
        $this->query = $query;
        $this->data = $data;
    }

    /**
     * Функция, возвращающая все предустановленные данные для отправки в бота
     * @return string JSON с данными
     */
    public function get() : string
    {
        $return = [
            'clientId' => $this->clientId
        ];
        if (!empty($this->query)) $return['query'] = $this->query;
        if (!empty($this->event)) $return['event'] = $this->event;
        if (!empty($this->data)) $return['data'] = $this->data;

        return json_encode($return);
    }

    public function setEvent(string $event) 
    {
        unset($this->query);
        $this->event = $event;
    }

    public function setData(array $data) 
    {
        $this->data = $data;
    }
}