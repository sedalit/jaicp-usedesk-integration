<?php

namespace Sedalit\JaicpUsedeskIntegration\Jaicp;

class JaicpApiRequestData {
    protected $clientId;
    protected $query;
    protected $data = [];

    function __construct($clientId, $query, $data = []) {
        $this->clientId = $clientId;
        $this->query = $query;
        $this->data = $data;
    }

    public function get(){
        return [
            'clientId' => $this->clientId,
            'query' => $this->query,
            'data' => $this->data
        ];
    }

    public function json(){
        return json_encode($this->get());
    }
}