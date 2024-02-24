<?php

namespace Sedalit\JaicpUsedeskIntegration\Core\Handlers;
use Sedalit\JaicpUsedeskIntegration\Http\Request;

interface IRequestHandler {

    /**
     * Функция-обработчик текущего запроса
     * @param Request $request Объект текущего запроса
    */
    public function handleRequest(Request $request);

    /**
     * Функция, проверяющая, подходит ли текущий запрос для обработки этим обработчиком
     * @return bool
    */
    public function canHandle() : bool;
}