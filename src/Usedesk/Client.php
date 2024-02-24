<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;

class Client{
    protected $id = 0;
    protected $name = "";
    protected $phones = [];
    protected $login = "";

    function __construct($data){
        if (isset($data['id'])){
            $this->id = $data['id'];
        }

        if (isset($data['name'])){
            $this->name = $data['name'];
        }

        if (isset($data['phones'])){
            $this->phones = $data['phones'];
        }
    }

    public function id(){
        return $this->id;
    }

    public function name(){
        return $this->name;
    }

    public function phones(){
        return $this->phones;
    }

    public function firstPhone(){
        return $this->phones[0] ?? null;
    }

    public function login(){
        return $this->login;
    }
}