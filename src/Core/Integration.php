<?php

namespace Sedalit\JaicpUsedeskIntegration\Core;
use Sedalit\JaicpUsedeskIntegration\Core\Handlers\Handler;
use Sedalit\JaicpUsedeskIntegration\Usedesk\Client;
use Sedalit\JaicpUsedeskIntegration\Usedesk\Ticket;

class Integration {

    /**
     * @var Ticket Объект текущего запроса (тикета) в Usedesk
     */
    protected $ticket;

    /**
     * @var Client Объект текущего клиента в Usedesk
     */
    protected $usedeskClient;

    /**
     * @var Handler Объект основного обработчика запросов
     */
    protected $handler;

    /**
     * @var ExceptionHandler Объект обработчика ошибок
     */
    protected $exceptionHandler;

    function __construct() 
    {
        $request = request();

        $this->usedeskClient = new Client($request->client());
        $this->ticket = new Ticket($request->ticket());
        $this->handler = new Handler($this->usedeskClient, $this->ticket);
        $this->exceptionHandler = new ExceptionHandler($this);
    }

    /**
     * Функция, вызывающая основной обработчик для обработки текущего запроса
     * @return array Массив с результатами обработки
     */
    public function handle() : array
    {
        return $this->handler->handle();
    }
}