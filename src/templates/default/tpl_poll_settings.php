<?php

echo "<b>$poll->title</b>\n\n";

$is_started = $poll->is_started ? "Да" : "Нет";
echo "Запущено: <b>$is_started</b>\n";

echo "Максимальная оценка: <b>$poll->max_rating</b>\n";

$is_free_votes = $poll->is_free_votes ? "Да" : "Нет";
echo "Свободное: <b>$is_free_votes</b>\n";

$is_open = $poll->is_open ? "Да" : "Нет";
echo "Открытое: <b>$is_open</b>\n\n";

if (count($poll_results)) {
    foreach ($poll_results as $result) {
        $rating = $result['rating'] < 0 ? '🚫' : $result['rating'];

        if ($result['id'] == $selected) {
            echo "$result[icon] - <b>[$result[title]]</b> [$rating]\n";
        } elseif ($message_id == 0) {
            echo "$result[icon] - $result[title] [$rating]\n";
        } else {
            echo "$result[icon] - <a href='t.me/votesmart_bot?start=select_{$message_id}_$result[id]'>$result[title]</a> [$rating]\n";
        }
    }
    echo "\nВы можете добавить пункты голосования (введите по одному на строку).";
} else {
    echo "Добавьте пункты голосования (введите по одному на строку).";
}


