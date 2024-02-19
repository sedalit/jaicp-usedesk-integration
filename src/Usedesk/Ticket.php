<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;

class Ticket {
    protected $id;
    protected $status_id;
    protected $subject;
    protected $client_id;
    protected $assignee_id;
    protected $group_id;
    protected $message;
    protected $files = [];
    protected $platform;
    protected $channel_id;

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

    public function id() {
        return $this->id;
    }

    public function statusId() {
        return $this->status_id;
    }

    public function subject() {
        return $this->subject;
    }

    public function assigneeId() {
        return $this->assignee_id;
    }

    public function groupId() {
        return $this->group_id;
    }

    public function files() {
        return ['files' => $this->files];
    }

    public function channelId() {
        return $this->channel_id;
    }
}