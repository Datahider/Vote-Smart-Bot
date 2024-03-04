<?php

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

echo serialize(new InlineKeyboardMarkup([
    [['text' => '🔙', 'callback_data' => 'back']],
    [['text' => '❌', 'callback_data' => "0_$name_id"],['text' => '🤔', 'callback_data' => "1_$name_id"],['text' => '👌', 'callback_data' => "2_$name_id"],['text' => '👍', 'callback_data' => "3_$name_id"],['text' => '🤘', 'callback_data' => "4_$name_id"]]
]));