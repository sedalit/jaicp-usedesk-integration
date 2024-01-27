<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;

class Ticket {
    protected $id;
    protected $statusId;
    protected $subject;
    protected $clientId;
    protected $assigneeId;
    protected $groupId;
    protected $message;
    protected $files = [];
    protected $platform;
    protected $channelId;

    function __construct($data) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function hasFiles() {
        return count($this->files) > 0;
    }

    public function message() {
        if ($this->hasFiles()) {
            return "";
        } else {
            return $this->message;
        }
    }

    public function platform() {
        return "";
    }

    public function channel() {
        return "";
    }
}