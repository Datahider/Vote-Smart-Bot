<?php

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

$line1 = [];
if ($poll->stage != 'created') {
    $line1[] = ['text' => '⏮', 'callback_data' => "stage_prev_$poll->id"];
}

if ($poll->stage != 'end') {
    $line1[] = ['text' => '⏭', 'callback_data' => "stage_next_$poll->id"];
}

$line1[] = ['text' => '️🧠', 'callback_data' => "toggle_free_votes_$poll->id"];
$line1[] = ['text' => '🗑️', 'callback_data' => "delete_$poll->id"];

$keyboard_array = [
    $line1,
    [['text' => '🔄 Обновить', 'callback_data' => "refresh_$poll->id"], ['text' => '➕ Добавить', 'callback_data' => "add_$poll->id"]],
];


if ($selected) {
    $keyboard_array[] = [
        ['text' => '✖️ Без оценки', 'callback_data' => "vote_0_$selected"],
        ['text' => '⛔️ Вето', 'callback_data' => "vote_never_$selected"],
    ];
    $keyboard_array[] = [
        ['text' => '🤔 (1)', 'callback_data' => "vote_1_$selected"],
        ['text' => '👌 (2)', 'callback_data' => "vote_2_$selected"],
        ['text' => '👍 (3)', 'callback_data' => "vote_3_$selected"],
        ['text' => '🤘 (4)', 'callback_data' => "vote_4_$selected"]
    ];
}

echo serialize(new InlineKeyboardMarkup($keyboard_array));