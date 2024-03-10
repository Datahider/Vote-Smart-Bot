<?php

namespace losthost\polabrain\service;

use losthost\telle\abst\AbstractBackgroundProcess;
use losthost\DB\DBList;
use losthost\polabrain\data\inline_message;
use losthost\polabrain\data\poll;
use losthost\telle\Bot;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

include_once 'src/functions.php';

class InlineUpdater extends AbstractBackgroundProcess {
    
    public function run() {
        
        if (!$this->lock()) {
            return;
        }
        
        try {
            $this->doUpdate();
        } catch (\Exception $ex) {
            error_log($ex->getMessage());
            error_log($ex->getTraceAsString());
        }
        
        $this->unlock();
    }
    
    protected function doUpdate() {
        $inline_list = new DBList(inline_message::class, ['needs_update' => 1]);
        $bot_username = Bot::param("bot_username", null);
        
        $texts = [];
        
        while ($im = $inline_list->next()) {
            if (empty($texts[$im->poll])) {
                $poll = new poll(['id' => $im->poll]);
                $text = getPollSharingText($poll);
                $texts[$poll->id] = $text;
            } else {
                $text = $texts[$im->poll];
            }

            $text = getPollSharingText($poll);
            $keyboard = new InlineKeyboardMarkup([
                [['text' => __('Take a part', $poll->language_code), 'url' => "t.me/$bot_username?start=$poll->id"]]
            ]);

            try {
                Bot::$api->editMessageText(null, null, $text, 'HTML', false, $keyboard, $im->id);
            } catch (\Exception $ex) {
                if ($ex->getMessage() == 'Bad Request: message is not modified: specified new message content and reply markup are exactly the same as a current content and reply markup of the message') {
                    // ignore
                } elseif ($ex->getMessage() == 'Bad Request: MESSAGE_ID_INVALID') {
                    $im->delete();
                    continue;
                }
                error_log($ex->getMessage(). " (". $ex->getCode(). ")");
            }

            $im->needs_update = false;
            $im->write();
        }
    }
    
    protected function lock() {
        // TODO - сделать реальную блокировку
        return true;
    }
    
    protected function unlock() {
        // TODO - сделать реальную разблокировку
    }
}
