<?php
use Sedalit\JaicpUsedeskIntegration\Core\Config;
use Sedalit\JaicpUsedeskIntegration\Http\Request;

if (!function_exists('request')) {
    function request() : Request
    {
        return Request::$instance;
    }
}

if (!function_exists('env')) {
    function env() : Config
    {
        return Config::self();
    }
}