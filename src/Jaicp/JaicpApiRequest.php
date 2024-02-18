<?php

namespace Sedalit\JaicpUsedeskIntegration\Jaicp;

use Sedalit\JaicpUsedeskIntegration\Core\ApiRequest;

class JaicpApiRequest extends ApiRequest {
    protected const PROTOCOL = "https://";
    protected $requestData;

    function __construct($url, $requestData, $settings = []){
        $this->url = self::PROTOCOL . $url;
        $this->requestData = $requestData;
        $this->setSettings($settings);
    }

    protected function setSettings($settings) {
        $this->settings = $settings + $this->defaultSettings();
    }

    protected function getSettings(){
        return $this->settings;
    }

    protected function defaultSettings() {
        return [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->requestData->get()
        ];
    }

}