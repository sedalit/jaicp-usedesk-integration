<?php
namespace Sedalit\JaicpUsedeskIntegration\Http;

class Request
{
    /**
     * The decoded JSON content for the request.
     *
     * @var array|null
     */
    protected $json;

    /**
     * The instance of the request
     *
     * @return $this
     */
    public static $instance = null;

    public function __construct(){
        $this->json = json_decode(file_get_contents("php://input"), true);
        if (self::$instance == null) self::$instance = $this;
    }

    /**
     * Get the JSON payload for the request.
     *
     * @return array|null
     */
    public function json(){
        return $this->json;
    }

    public function client() {
        return $this->json['client'] ?? [];
    }

    public function ticket() {
        return $this->json['ticket'] ?? [];
    }

    public function chatId() {
        return $this->json['chat_id'] ?? false;
    }

    public function platform() {
        return $this->json['platform'] ?? null;
    }
}
