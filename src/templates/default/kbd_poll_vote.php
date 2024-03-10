<?php

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

$line1 = [['text' => '🔄 Обновить', 'callback_data' => "refresh_$poll->id"]];

$keyboard_array[] = $line1;

if ($selected) {
    $line2 = [['text' => '✖️ Без оценки', 'callback_data' => "vote_0_$selected"]];
    if ($poll->can_block) {
        $line2[] = ['text' => '⛔️ Вето', 'callback_data' => "vote_never_$selected"];
    }
    $keyboard_array[] = $line2;
    $keyboard_array[] = [
        ['text' => '🤔 (1)', 'callback_data' => "vote_1_$selected"],
        ['text' => '👌 (2)', 'callback_data' => "vote_2_$selected"],
        ['text' => '👍 (3)', 'callback_data' => "vote_3_$selected"],
        ['text' => '🤘 (4)', 'callback_data' => "vote_4_$selected"]
    ];
}

echo serialize(new InlineKeyboardMarkup($keyboard_array));    

