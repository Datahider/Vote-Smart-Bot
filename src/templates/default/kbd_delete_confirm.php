<?php

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

echo serialize(new InlineKeyboardMarkup([
    [['text' => '💣 Да, удалить', 'callback_data' => "confirm_delete_$poll->id"], ['text' => '🔙 Нет! Не удалять!', 'callback_data' => "cancel_delete_$poll->id"]]
]));
