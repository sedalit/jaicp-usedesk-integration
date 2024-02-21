<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;
use Sedalit\JaicpUsedeskIntegration\Core\ApiRequest;

class UsedeskApiRequest extends ApiRequest {
    protected $requestData;

    function __construct($url, $requestData, $settings = []){
        $this->url = UsedeskInterface::USEDESK_HOST . $url;
        $this->requestData = $requestData;
        $this->settings = $this->setSettings($settings);
    }

    public function getSettings() {
        return $this->settings;
    }

    protected function setSettings($settings) {
        $data = [
            'api_token' => env()->tokens('usedeskApiToken'),
        ];

        $requestData = $this->requestData->get();

        $data = $data + $requestData[0];

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