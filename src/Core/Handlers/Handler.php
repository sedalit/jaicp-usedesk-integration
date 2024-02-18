<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers;

class Handler {
    protected $usedeskClient;
    protected $ticket;
    protected $handlers = [];
    protected $handlerBooter;

    function __construct($usedeskClient, $ticket) {
        $this->usedeskClient = $usedeskClient;
        $this->ticket = $ticket;
        $this->handlerBooter = new HandlerBoot();
    }

    public function handle() {
        $this->initHandlers();

        $messagesResult = $this->handleMessage();
        $triggersResult = $this->handleTrigger();

        return ['messages' => $messagesResult, 'triggers' => $triggersResult];
    }

    protected function handleMessage() {
        if (isset($this->handlers['message'])){
            $result = [];
            foreach ($this->handlers['message'] as $handler) {
                if (call_user_func_array([$handler, 'canHandle'], [])) {
                    $result[$handler] = call_user_func_array([$handler, 'handleRequest'], [request()]);
                } else {
                    $result[$handler] = "Can't handle";
                }
            }
 
            return $result;
        }
        return "No handlers booted";
    }

    protected function handleTrigger() {
        if (isset($this->handlers['trigger'])){
            $result = [];
            foreach ($this->handlers['trigger'] as $handler) {
                if (call_user_func_array([$handler, 'canHandle'], [])) {
                    $result[$handler] = call_user_func_array([$handler, 'handleRequest'], [request()]);
                } else {
                    $result[$handler] = "Can't handle";
                }
            }

            return $result;
        }
        return "No handlers booted";
    }

    protected function initHandlers() {
        $this->handlers = $this->handlerBooter->boot($this->ticket, $this->usedeskClient);
    }
}