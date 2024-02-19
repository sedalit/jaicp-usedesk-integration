<?php

namespace Sedalit\JaicpUsedeskIntegration\Jaicp;

class JaicpApiRequestData {
    protected $clientId;
    protected $query;
    protected $data = [];
    protected $event;

    function __construct($clientId, $query, $data = []) {
        $this->clientId = $clientId;
        $this->query = $query;
        $this->data = $data;
    }

    public function get(){
        $return = [
            'clientId' => $this->clientId
        ];
        if (!empty($this->query)) $return['query'] = $this->query;
        if (!empty($this->event)) $return['event'] = $this->event;
        if (!empty($this->data)) $return['data'] = $this->data;

        return json_encode($return);
    }

    public function setEvent(string $event) {
        unset($this->query);
        $this->event = $event;
    }

    public function setData(array $data) {
        $this->data = $data;
    }

    public function json(){
        return json_encode([$this->get()]);
    }
}