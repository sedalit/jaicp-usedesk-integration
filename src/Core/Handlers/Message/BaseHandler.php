<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers\Message;
use Sedalit\JaicpUsedeskIntegration\Jaicp\JaicpInterface;
use Sedalit\JaicpUsedeskIntegration\Usedesk\UsedeskInterface;
use Sedalit\JaicpUsedeskIntegration\Usedesk\Ticket;
use Sedalit\JaicpUsedeskIntegration\Usedesk\Client;

abstract class BaseHandler {

    /**
     * @var UsedeskInterface Объект интерфейса Usedesk
     */
    protected $usedeskInterface;

    /**
     * @var JaicpInterface Объект интерфейса JAICP
     */
    protected $jaicpInterface;

    /**
     * @var Ticket Объект текущего запроса (тикета) в Usedesk
     */
    protected $ticket;

    /**
     * @var Client Объект текущего клиента в Usedesk
     */
    protected $usedeskClient;

    public function __construct(Ticket $ticket, Client $usedeskClient)
    {
        $this->boot($ticket, $usedeskClient);
    }

    /**
     * Функция инициализации обработчика запроса
     * @param Ticket $ticket Объект текущего запроса (тикета) в Usedesk
     * @param Client $usedeskClient Объект текущего клиента в Usedesk
     * @return BaseHandler Готовый для работы обработчик
    */
    protected function boot(Ticket $ticket, Client $usedeskClient) : BaseHandler
    {
        $this->ticket = $ticket;
        $this->usedeskClient = $usedeskClient;

        $this->usedeskInterface = new UsedeskInterface($ticket, request()->chatId());
        $this->jaicpInterface = new JaicpInterface($ticket->id(), $ticket->message(), []);
        
        return $this;
    }
}