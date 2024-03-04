<?php

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

$keyboard_array = [
    [['text' => '🔄 Обновить', 'callback_data' => "refresh_$poll->id"]],    
];

if ($selected) {
    $keyboard_array[] = [
        ['text' => '✖️ Без оценки', 'callback_data' => "vote_0_$selected"],
        ['text' => '⛔️ Вето', 'callback_data' => "vote_never_$selected"],
    ];
    $keyboard_array[] = [
        ['text' => '🤔', 'callback_data' => "vote_1_$selected"],
        ['text' => '👌', 'callback_data' => "vote_2_$selected"],
        ['text' => '👍', 'callback_data' => "vote_3_$selected"],
        ['text' => '🤘', 'callback_data' => "vote_4_$selected"]
    ];
}

echo serialize(new InlineKeyboardMarkup($keyboard_array));    

