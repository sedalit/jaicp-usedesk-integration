<?php

namespace Sedalit\JaicpUsedeskIntegration\Jaicp;

use Sedalit\JaicpUsedeskIntegration\Core\ApiRequest;

class JaicpApiRequest extends ApiRequest {
    protected $requestData;

    function __construct($url, $settings = [], $requestData){
        $this->url = $url;
        $this->settings = $settings;
        $this->requestData = $requestData;
    }

    protected function getSettings(){
        return [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->requestData->json()
        ];
    }


}