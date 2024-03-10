<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerChosenInlineResult;
use losthost\polabrain\data\inline_message;

class InlineResult extends AbstractHandlerChosenInlineResult {
    
    protected function check(\TelegramBot\Api\Types\Inline\ChosenInlineResult &$chosen_inline_result): bool {
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Inline\ChosenInlineResult &$chosen_inline_result): bool {
        $im = new inline_message(['id' => $chosen_inline_result->getInlineMessageId()], true);
        if ($im->isNew()) {
            $im->poll = $chosen_inline_result->getResultId();
            $im->needs_update = false;
            $im->write();
        }
        return true;
    }
}
