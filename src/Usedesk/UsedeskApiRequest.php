<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;
use Sedalit\JaicpUsedeskIntegration\Core\ApiRequest;

class UsedeskApiRequest extends ApiRequest {
    protected $requestData;

    function __construct($url, $settings = [], $requestData){
        $this->url = $url;
        $this->settings = $settings;
        $this->requestData = $requestData;
    }

    protected function getSettings() {
        $data = [
            'api_token' => $_ENV['usedesk_api_key'],
        ];

        $requestData = $this->requestData->get();

        $data = array_merge($data, $requestData);

        return [
            CURLOPT_URL => $this->url,
            CURLOPT_USERAGENT => 'PHP-MCAPI/2.0',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $data
        ];
    }
}