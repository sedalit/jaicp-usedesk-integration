<?php

namespace Sedalit\JaicpUsedeskIntegration\Jaicp;

use Sedalit\JaicpUsedeskIntegration\Core\ApiRequest;

class JaicpApiRequest extends ApiRequest {
    protected const PROTOCOL = "https://";

    /**
     * @var JaicpApiRequestData Объект, хранящий данные для отправки в API JAICP
     */
    protected $requestData;

    function __construct(string $url, JaicpApiRequestData $requestData, array $settings = [])
    {
        $this->url = self::PROTOCOL . $url;
        $this->requestData = $requestData;
        $this->setSettings($settings);
    }

    protected function setSettings(array $settings) : void
    {
        $this->settings = $settings + $this->defaultSettings();
    }

    protected function getSettings() : array
    {
        return $this->settings;
    }

    /**
     * Функция, возвращающая стандартные настройки CURL для отправки запроса к API JAICP
     */
    protected function defaultSettings() : array
    {
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