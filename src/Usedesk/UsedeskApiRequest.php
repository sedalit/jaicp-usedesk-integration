<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;
use Sedalit\JaicpUsedeskIntegration\Core\ApiRequest;

class UsedeskApiRequest extends ApiRequest {

    /**
     * @var UsedeskApiRequestData Объект, хранящий данные для отправки в Usedesk
     */
    protected $requestData;

    function __construct($url, $requestData, $settings = [])
    {
        $this->url = UsedeskInterface::USEDESK_HOST . $url;
        $this->requestData = $requestData;
        $this->setSettings($settings);
    }

    public function getSettings() : array
    {
        return $this->settings;
    }

    protected function setSettings($settings) : void
    {
        $data = [
            'api_token' => env()->tokens('usedeskApiToken'),
        ];

        $requestData = $this->requestData->get();

        $data = $data + $requestData;

        $this->settings = [
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