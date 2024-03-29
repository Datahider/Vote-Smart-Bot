<?php
use losthost\telle\Bot;

echo "<b>$poll->title</b>\n\n";

echo 'Этап: <b>'. __($poll->stage). "</b> (⏮⏭)\n";

$is_free_votes = $poll->is_free_votes ? "Мозговой штурм" : "Опрос";
echo "️Тип: <b>$is_free_votes</b> (🧠)\n";

$can_block = $poll->can_block ? "Использовать" : "Не использовать";
echo "Право вето: <b>$can_block</b> (🔏)\n\n";

if (count($poll_results)) {

    $bot_username = Bot::param("bot_username", null);
    foreach ($poll_results as $result) {
        if ($poll->stage == 'voting' || $poll->stage == 'end') {
            $rating = ' ['. ($result['rating'] < 0 ? '🚫' : $result['rating']). ']';
        } else { 
            $rating = '';
        }

        if ($result['id'] == $selected) {
            echo "$result[icon] - <b>[$result[title]]</b>$rating\n";
        } elseif ($message_id == 0 || $poll->stage != 'voting') {
            echo "$result[icon] - $result[title]$rating\n";
        } else {
            echo "$result[icon] - <a href='t.me/$bot_username?start=s_{$message_id}_$result[id]'>$result[title]</a>$rating\n";
        }
    }
} else {
    echo "<i>Добавляйте новые варианты голосования отправляя их боту.</i>\n";
}


