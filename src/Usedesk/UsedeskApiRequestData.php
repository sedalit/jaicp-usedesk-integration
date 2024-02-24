<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;

class UsedeskApiRequestData {
    protected $data;

    function __construct($data) {
        $this->data = $data;
    }

    function get() {
        return $this->data;
    }
}