<?php
require 'vendor/autoload.php';

use Sedalit\JaicpUsedeskIntegration\Core\Integration;
use Sedalit\JaicpUsedeskIntegration\Http\Request;

$request = new Request();

$integration = new Integration();

return $integration->handle();
