<b><?=$poll->title;?></b>

<?php 
if ($poll->stage == 'created') : ?>
Это голосование ещё не началось. 

Попробуйте через некоторое время нажать кнопку <b>Обновить</b>

<?php else : 
    if ($poll->stage == 'ideas') : ?>
<u>Этап сбора идей</u>

Добавляйте свои варианты в список идей с помощью кнопки <b>[➕Добавить]</b>

<?php elseif ($poll->stage == 'voting') : ?>
<u>Этап голосования</u>

Выберите вариант нажатием на него и поставьте одну из оценок с помощью кнопок.

Каждую оценку можно использовать не более одного раза. При повторном использовании той же оценки, предыдущая будет автоматически изменена на меньшую. 

Оценки можно изменять в процессе голосования.

<?php else : ?>
<u>Голосование завершено</u>

<?php endif; 

if (count($poll_results)) {
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
            echo "$result[icon] - <a href='t.me/votesmart_bot?start=select_{$message_id}_$result[id]'>$result[title]</a>$rating\n";
        }
    }
}

endif;    





