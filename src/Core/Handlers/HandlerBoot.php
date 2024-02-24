<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers;
use Sedalit\JaicpUsedeskIntegration\Core\Handlers\Message\MessageFromClient;
use Sedalit\JaicpUsedeskIntegration\Usedesk\Client;
use Sedalit\JaicpUsedeskIntegration\Usedesk\Ticket;

class HandlerBoot {
    
    /**
     * @var array Массив обработчиков для запуска
     */
    protected $boot = [
        'message' => [
            MessageFromClient::class,
        ],
        'trigger' => []
    ];

    /**
     * Функция, инициализирующая каждый обработчик
     * @param Ticket $ticket Объект текущего запроса (тикета) в Usedesk
     * @param Client $usedeskClient Объект текущего клиента в Usedesk
     * @param string $requestType Тип запроса (сообщение или триггер)
     */
    public function boot(Ticket $ticket, Client $usedeskClient, string $requestType = "message") : array
    {
        $handlers = [];
        
        if (!isset($this->boot[$requestType])) return $handlers;

        foreach ($this->boot[$requestType] as $handler) {
            $handlers[] = new $handler($ticket, $usedeskClient);
        }
        
        return $handlers;
    }
}