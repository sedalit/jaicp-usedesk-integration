<?php

namespace Sedalit\JaicpUsedeskIntegration\Core;

abstract class ApiRequest {
    protected $url;
    protected $settings;
    public $response;

    function __construct($url, $settings = []){
        $this->url = $url;
        $this->settings = $settings;
    }

    protected abstract function getSettings();

    public function make() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        foreach ($this->settings as $key => $value){
            curl_setopt($curl, $key, $value);
        }
        $response = curl_exec($curl);
        curl_close($curl);
        $this->response = json_decode($response, true);
        file_put_contents('logs/' . date("Y-m-d") . '.txt', date("Y-m-d H:i:s") . ": " . "Request: " . print_r($this->settings, true) . "\nResponse: " . print_r($this->response, true), FILE_APPEND);
        return $this->response;
    }

    protected function setSettings($settings){
        $this->settings = $settings;
    }
}