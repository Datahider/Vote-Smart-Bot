<?php

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

echo serialize(new InlineKeyboardMarkup([
    [['text' => 'Мужское', 'callback_data' => "m"],['text' => 'Женское', 'callback_data' => "f"]]
]));