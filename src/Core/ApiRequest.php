<?php

namespace Sedalit\JaicpUsedeskIntegration\Core;

abstract class ApiRequest {
    /**
     * @var string URL, к которому необходимо выполнить запрос
     */
    protected $url;

    /**
     * @var array Дополнительные поля, которые нужно отправить вместе с запросом
     */
    protected $settings;

    /**
     * @var mixed Ответ, полученный после выполнения запроса
     */
    public $response;

    function __construct($url, $settings = [])
    {
        $this->url = $url;
        $this->settings = $settings;
    }

    /**
     * Функция, возвращающая все установленные поля, которые необходимо отправить вместе с запросом
     * @return array
     */
    protected abstract function getSettings() : array;

    /**
     * Функция, выполняющая запрос к установленному URL
     * @return array Результат запроса
     */
    public function make() 
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        foreach ($this->settings as $key => $value){
            curl_setopt($curl, $key, $value);
        }
        $response = curl_exec($curl);
        curl_close($curl);
        $this->response = json_decode($response, true);
        $data = ["Request: " => $this->settings, "Response: " => $this->response];
        Logger::log('handled', $data);
        return $this->response;
    }

    protected function setSettings(array $settings) : void
    {
        $this->settings = $settings;
    }
}