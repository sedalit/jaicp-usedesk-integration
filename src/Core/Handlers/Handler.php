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
                if (call_user_func_array([$handler, 'canHandle'], [1,2])) {
                    $result[] = call_user_func_array([$handler, 'handleRequest'], [request()]);
                }
            }
 
            return $result;
        }
        
        
    }

    protected function handleTrigger() {
        if (isset($this->handlers['trigger'])){
            $result = [];
            foreach ($this->handlers['trigger'] as $handler) {
                if (call_user_func_array([$handler, 'canHandle'], [1,2])) {
                    $result[] = call_user_func_array([$handler, 'handleRequest'], [request()]);
                }
            }

            return $result;
        }
    }

    protected function initHandlers() {
        $this->handlers = $this->handlerBooter->boot();
    }
}