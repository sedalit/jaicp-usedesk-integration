<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers;

interface IRequestHandler {
    public function handleRequest($request);
    public function canHandle();
}