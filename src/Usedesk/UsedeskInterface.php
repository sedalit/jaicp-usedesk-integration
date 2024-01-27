<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;

class UsedeskInterface {
    protected $operatorId;
    protected $chatId;
    protected $ticketId;
    protected $platform;
    protected $usedeskChannel;

    function __construct($operatorId, $chatId, $ticketId, $platform, $usedeskChannelId){
        if ($operatorId) {
            $this->operatorId = $operatorId;
        } else {
            $this->operatorId = "";
        }

        $this->chatId = $chatId;
        $this->ticketId = $ticketId;
        $this->platform = $platform;
        $this->setUsedeskChannel($usedeskChannelId);
    }

    public function updateTicket($ticketData) {
        $requestData = new UsedeskApiRequestData($ticketData);
        $request = new UsedeskApiRequest('https://api.usedesk.ru/update/ticket', [], $requestData);

        return $request->make();
    }

    public function switchOperator($targetOperatorId) {
        if ($this->operatorId == $targetOperatorId) return;
        $this->operatorId = $targetOperatorId;

        $requestData = new UsedeskApiRequestData([
            'chat_id' => $this->chatId,
            'user_id' => $this->operatorId 
        ]);
        $request = new UsedeskApiRequest('https://api.usedesk.ru/chat/changeAssignee', [], $requestData);

        return $request->make();
    }

    public function switchOperatorGroup($operatorGroupId) {
        $requestData = new UsedeskApiRequestData(
            [
                'ticket_id' => $this->ticketId,
                'group_id' => $operatorGroupId,
                'user_id' => ""
            ]
        );
    }

    public function sendMessage($data) {
        $wasTransitionToOperator = $this->wasTransitionToOperator($data['bot_answer']);

        if ($wasTransitionToOperator) {
            $this->switchOperatorGroup($this->operatorGroup());
        } else {
            $this->switchOperator($_ENV['default_operator_id']);
        }

        $sendMessageResult = $this->sendMessageToUsedesk($data['bot_answer'] ?? []);
        $sendFilesResult = $this->sendFilesToUsedesk($data['files'] ?? []);

        return ['messages' => $sendMessageResult, 'files' => $sendFilesResult, 'wasTransition' => $wasTransitionToOperator];
    }

    protected function sendMessageToUsedesk($messages) {
        $result = [];

        foreach ($messages as $message) {
            $requestData = new UsedeskApiRequestData([
                [
                    'chat_id' => $this->chatId,
                    'user_id'=> $_ENV['default_operator_id'],
                    'text' => $message['text']
                ]
            ]);
            $request = new UsedeskApiRequest('https://api.usedesk.ru/chat/sendMessage', [], $requestData);
            $result[] = $request->make();
        }

        return $result;
    }

    protected function sendFilesToUsedesk($files) {
        $result = [];
        $curlFiles = $this->prepareFiles($files);
        foreach ($curlFiles as $file) {
            $requestData = new UsedeskApiRequestData([
                'ticket_id' => $this->ticketId,
                'user_id'=> $_ENV['default_operator_id'],
                'message' => "Файл:",
                'type' => 'public',
                'files[]' => $file,
                'from' => 'user'
            ]);
            $request = new UsedeskApiRequest('https://api.usedesk.ru/create/comment', [], $requestData);

            $result[] = $request->make();
        }

        return $result;
    }

    protected function prepareFiles($files) {
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

    protected function wasTransitionToOperator($answers) {
        foreach ($answers as $key => $value) {
            if ($value['state'] == $_ENV['operator_state'] || $value['transition'] == $_ENV['operator_state']){
                return true;
            }
        }

        return false;
    }

    protected function operatorGroup() {
        return "";
    }

    protected function setUsedeskChannel($channelId) {
        $this->usedeskChannel = "";
    }
}