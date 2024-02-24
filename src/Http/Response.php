<?php

namespace Sedalit\JaicpUsedeskIntegration\Http;

class Response {

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $statusText;

    /**
     * @var array
     */
    protected $headers;

    function __construct($statusCode, $statusText, $content, $headers){
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;
        $this->content = $content;
        $this->headers = $headers;
    }

    public function send() {
        $this->sendHeaders();
        $this->sendContent();

        return $this;
    }

    /**
     * Sends HTTP headers.
     *
     * @return $this
     */
    protected function sendHeaders() {
        if (headers_sent()) {
            return $this;
        }

        foreach ($this->headers as $key => $values) {
            $replace = 0 === strcasecmp($key, 'Content-Type');
            foreach ($values as $value) {
                header($key.': '.$value, $replace, $this->statusCode);
            }
        }

        return $this;
    }

    /**
     * Sends content for the current web response.
     *
     * @return $this
     */
    protected function sendContent()
    {
        echo $this->content;

        return $this;
    }
}