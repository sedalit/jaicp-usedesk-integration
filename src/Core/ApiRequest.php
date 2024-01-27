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
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        foreach ($this->settings as $key => $value){
            curl_setopt($curl, $key, $value);
        }

        $response = curl_exec($curl);
        curl_close($curl);

        $this->response = json_decode($response, true);

        return $this->response;
    }

    protected function setSettings($settings){
        $this->settings = $settings;
    }
}