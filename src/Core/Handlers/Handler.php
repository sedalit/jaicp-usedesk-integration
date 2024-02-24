<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers;

use Sedalit\JaicpUsedeskIntegration\Usedesk\Client;
use Sedalit\JaicpUsedeskIntegration\Usedesk\Ticket;

class Handler {
    /**
     * @var Client Объект текущего клиента в Usedesk
    */
    protected $usedeskClient;

    /**
     * @var Ticket Объект текущего запроса (тикета) в Usedesk
    */
    protected $ticket;

    /**
     * @var array Массив инициализированных обработчиков
    */
    protected $handlers = [];

    /**
     * @var HandlerBoot Объект инициализатора обработчиков
    */
    protected $handlerBooter;

    function __construct(Client $usedeskClient, Ticket $ticket) 
    {
        $this->usedeskClient = $usedeskClient;
        $this->ticket = $ticket;
        $this->handlerBooter = new HandlerBoot();
    }

    /**
     * Функция, вызывающая все подходящие обработчики для текущего запроса
     * @return array Массив с результатами обработки запроса
    */
    public function handle() : array
    {
        $requestType = request()->chatId() ? "message" : "trigger";
        $this->initHandlers($requestType);

        return $this->handleTypedRequest();
    }

    /**
     * Функция, проверяющая условия запуска обработчика и запускающая его для обработки
     * @return array Результаты работы обработчиков
    */
    protected function handleTypedRequest() : array
    {
        $result = [];
        foreach ($this->handlers as $handler) {
            if (call_user_func_array([$handler, 'canHandle'], [])) {
                $result[$handler] = call_user_func_array([$handler, 'handleRequest'], [request()]);
            } else {
                $result[$handler] = "Can't handle";
            }
        }
        var_dump($result);
        return $result;
    }

    /**
     * Функция, инициализирующая все обработчики для текущего типа запроса
     * @param string $requestType Тип запроса (сообщение или триггер)
    */
    protected function initHandlers(string $requestType) : void
    {
        $this->handlers = $this->handlerBooter->boot($this->ticket, $this->usedeskClient, $requestType);
    }
}