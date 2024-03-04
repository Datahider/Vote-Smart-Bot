<?php

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

if ($poll->is_started) {
    $start_icon = '⏹';
} else {
    $start_icon = '▶️';
}

$keyboard_array = [
    [['text' => $start_icon, 'callback_data' => "togle_start_$poll->id"], ['text' => '📈️', 'callback_data' => "togle_rating_$poll->id"], ['text' => '️✍️', 'callback_data' => "togle_free_votes_$poll->id"], ['text' => '🗑️', 'callback_data' => "delete_$poll->id"]],
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