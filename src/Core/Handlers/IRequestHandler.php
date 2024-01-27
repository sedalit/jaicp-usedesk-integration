<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers;

interface RequestHandler {
    public function handleRequest($request);
    public function canHandle();
}