<b><?=$poll->title;?></b>

Выберите вариант и поставьте одну из оценок с помощью кнопок.
Каждую оценку можно использовать только один раз. 
Оценки можно изменять в процессе голосования.

<?php

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
}





