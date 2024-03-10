<?php

namespace losthost\polabrain\service;

use losthost\telle\abst\AbstractBackgroundProcess;
use losthost\telle\Bot;

class MessageDeleter extends AbstractBackgroundProcess {
    
    public function run() {
        
        $params = explode(" ", $this->param);
        try {
            Bot::$api->deleteMessage($params[0], $params[1]);
        } catch (\Exception $e) {}
    }
}
