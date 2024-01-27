<?php
use Sedalit\JaicpUsedeskIntegration\Http\Request;

if (!function_exists('request')) {
    function request() : Request
    {
        return Request::$instance;
    }
}