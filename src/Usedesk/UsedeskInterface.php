<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;

class UsedeskInterface {
    /**
     * @var string Хост, на котором лежит Юздеск
     */
    public const USEDESK_HOST = 'https://api.usedesk.ru';

    /**
     * @var string API метод для обновления запроса
     */
    protected const UPDATE_TICKET_METHOD = '/update/ticket';

    /**
     * @var string API метод для смены ответственного в чате
     */
    protected const CHANGE_ASSIGNEE_METHOD = '/chat/changeAssignee';

    /**
     * @var string API метод для создания комментария
     */
    protected const CREATE_COMMENT_METHOD = '/create/comment';

    /**
     * @var string ID текущего ответственного за запрос
     */
    protected $operatorId;

    /**
     * @var string ID текущего чата
     */
    protected $chatId;

    /**
     * @var string ID текущего запроса
     */
    protected $ticketId;

    /**
     * @var string Название платформы, на которой расположен текущий канал
     */
    protected $platform;

    /**
     * @var string Название канала, из которого пришёл текущий запрос
     */
    protected $usedeskChannel;

    function __construct($ticket, $chatId)
    {
        if ($ticket->assigneeId()) {
            $this->operatorId = $ticket->assigneeId();
        } else {
            $this->operatorId = env()->usedesk('botUserId');
        }

        $this->chatId = $chatId;
        $this->ticketId = $ticket->id();
        $this->platform = $ticket->platform();
        $this->setUsedeskChannel($ticket->channelId());
    }

    /**
     * Функция, обновляющая запрос (тикет)
     * @param array Массив с данными, которые необходимо обновить
     * @return array Результат запроса
     */
    public function updateTicket($ticketData)
     {
        $requestData = new UsedeskApiRequestData($ticketData);
        $request = new UsedeskApiRequest(self::UPDATE_TICKET_METHOD, $requestData);

        return $request->make();
    }

    /**
     * Функция, обновляющая ответственного за запрос (тикет)
     * @param string $targetOperatorId ID оператора, на которого нужно перевести запрос
     * @return array Результат запроса
     */
    public function switchOperator($targetOperatorId)
    {
        if ($this->operatorId == $targetOperatorId) return [];
        $this->operatorId = $targetOperatorId;

        $requestData = new UsedeskApiRequestData([
            'chat_id' => $this->chatId,
            'user_id' => $this->operatorId 
        ]);
        $request = new UsedeskApiRequest(self::CHANGE_ASSIGNEE_METHOD, $requestData);

        return $request->make();
    }

    /**
     * Функция, обновляющая группу ответственных за запрос (тикет)
     * @param string $operatorGroupId ID группы, на которую нужно перевести запрос
     * @return array Результат запроса
     */
    public function switchOperatorGroup($operatorGroupId)
    {
        $requestData = new UsedeskApiRequestData(
            [
                'ticket_id' => $this->ticketId,
                'group_id' => $operatorGroupId,
                'user_id' => env()->usedesk('botUserId'),
            ]
        );

        $reuest = new UsedeskApiRequest(self::UPDATE_TICKET_METHOD, $requestData);

        return $reuest->make();
    }

    /**
     * Функция, обрабатывающая ответ от бота и отправляющая сообщения в Usedesk
     * @param array $answerData Массив ответов от бота
     * @return array Массив результатов отправки сообщения
     */
    //TODO: Вынести $answersData в отдельный класс-прослойку
    public function sendMessage($answerData)
    {
        $wasTransitionToOperator = $this->wasTransitionToOperator($answerData['data']['replies']);

        if ($wasTransitionToOperator) {
            $this->switchOperatorGroup($this->operatorGroup());
        } else {
            $this->switchOperator(env()->usedesk('botUserId'));
        }

        $sendMessageResult = $this->sendMessageToUsedesk($answerData['data']['replies'] ?? []);
        $sendFilesResult = $this->sendFilesToUsedesk($answerData['files'] ?? []);

        return ['messages' => $sendMessageResult, 'files' => $sendFilesResult, 'wasTransition' => $wasTransitionToOperator];
    }

    /**
     * Функция, отправляющая сообщения от лица ответсвенного в Usedesk
     * @param array $messages Массив сообщений
     * @return array Массив результатов отправки сообщений
     */
    protected function sendMessageToUsedesk($messages)
    {
        $result = [];

        foreach ($messages as $message) {
            if (!isset($message['text'])) continue;

            $requestData = new UsedeskApiRequestData(
                [
                    'ticket_id' => $this->ticketId,
                    'user_id'=> $this->operatorId,
                    'message' => $message['text'],
                    'type' => 'public',
                    'from' => 'user'
                ]
            );
            $result[] = $this->createComment($requestData);
        }

        return $result;
    }

    /**
     * Функция, отправляющая файлы в чат от лица ответсвенного в Usedesk
     * @param array $messages Массив файлов
     * @return array Массив результатов отправки файлов
     */
    protected function sendFilesToUsedesk($files)
    {
        $result = [];
        $curlFiles = $this->prepareFiles($files);
        foreach ($curlFiles as $file) {
            $requestData = new UsedeskApiRequestData([
                'ticket_id' => $this->ticketId,
                'user_id'=> $this->operatorId,
                'message' => "Файл:",
                'type' => 'public',
                'files[]' => $file,
                'from' => 'user'
            ]);
            
            $result[] = $this->createComment($requestData);
        }

        return $result;
    }

    /**
     * Функция, отправляющая запрос в Usedesk для создания комментария в тикете
     * @param UsedeskApiRequestData $requestData Данные, с которыми нужно создать комментарий
     * @return array Результат запроса
     */
    protected function createComment($requestData)
    {
        $request = new UsedeskApiRequest(self::CREATE_COMMENT_METHOD, $requestData);
        return $request->make();
    }

    /**
     * Функция, подготавливающая файлы для отправки в Usedesk
     * @param array $files Массив файлов
     * @return array Массив обработанных файлов
     */
    protected function prepareFiles(array $files) : array
    {
        $preparedFiles = [];
        foreach ($files as $file) {
            $path = "files/". $file['fileName'];
            if (!file_exists($path)) file_put_contents($path, file_get_contents($file['fileUrl']));

            $fileNameWithFullPath = realpath($path);
            $curlFile = curl_file_create($fileNameWithFullPath);
            $preparedFiles[] = $curlFile;
        }

        return $preparedFiles;
    }

    /**
     * Функция, определяющая, был ли в ответе бота перевод на оператора
     * @param array $answers Массив ответов от бота
     * @return bool Был ли перевод на оператора
     */
    protected function wasTransitionToOperator($answers) : bool
    {
        foreach ($answers as $key => $value) {
            if (isset($value['type']) && $value['type'] === 'switch') {
                return true;
            }
        }

        return false;
    }

    /**
     * Функция, возвращающая ID операторской группы, назначенной к текущему каналу
     */
    protected function operatorGroup()
    {
        $channel = $this->usedeskChannel;
        $operatorGroup = env()->operatorGroups('defaultOperatorGroupID');
        if ($channel) {
            $operatorGroup = env()->operatorGroups($channel);
        }
        
        return $operatorGroup;
    }

    //TODO: Добавить возможность фиксирования названия канала по его ID
    protected function setUsedeskChannel($channelId)
    {
        $this->usedeskChannel = "";
    }
}