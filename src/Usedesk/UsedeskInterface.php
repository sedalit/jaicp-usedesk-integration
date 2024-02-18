<?php

namespace Sedalit\JaicpUsedeskIntegration\Usedesk;

class UsedeskInterface {
    protected $operatorId;
    protected $chatId;
    protected $ticketId;
    protected $platform;
    protected $usedeskChannel;

    function __construct(Ticket $ticket, $chatId){
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

    public function updateTicket($ticketData) {
        $requestData = new UsedeskApiRequestData($ticketData);
        $request = new UsedeskApiRequest('https://api.usedesk.ru/update/ticket', $requestData);

        return $request->make();
    }

    public function switchOperator($targetOperatorId) {
        if ($this->operatorId == $targetOperatorId) return;
        $this->operatorId = $targetOperatorId;

        $requestData = new UsedeskApiRequestData([
            'chat_id' => $this->chatId,
            'user_id' => $this->operatorId 
        ]);
        $request = new UsedeskApiRequest('https://api.usedesk.ru/chat/changeAssignee', $requestData);

        return $request->make();
    }

    public function switchOperatorGroup($operatorGroupId) {
        $requestData = new UsedeskApiRequestData(
            [
                'ticket_id' => $this->ticketId,
                'group_id' => $operatorGroupId,
                'user_id' => env()->usedesk('botUserId'),
            ]
        );
    }

    public function sendMessage($answerData) {
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

    protected function sendMessageToUsedesk($messages) {
        $result = [];

        foreach ($messages as $message) {
            $requestData = new UsedeskApiRequestData([
                [
                    'ticket_id' => $this->ticketId,
                    'user_id'=> $this->operatorId,
                    'message' => $message['text'],
                    'type' => 'public',
                    'from' => 'user'
                ]
            ]);
            $result[] = $this->createComment($requestData);
        }

        return $result;
    }

    protected function sendFilesToUsedesk($files) {
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

    protected function createComment(UsedeskApiRequestData $requestData) {
        $request = new UsedeskApiRequest('https://api.usedesk.ru/create/comment', $requestData);
        return $request->make();
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
        $channel = $this->usedeskChannel;
        $operatorGroup = env()->operatorGroups($channel);
        return $operatorGroup;
    }

    protected function setUsedeskChannel($channelId) {
        $this->usedeskChannel = "";
    }
}