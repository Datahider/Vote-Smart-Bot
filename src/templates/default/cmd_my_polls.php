<?php

if (count($polls)) {

    echo "<b>Ваши голосования</b>\n\n";

    foreach ($polls as $poll) {
        echo "/$poll->id. - $poll->title\n";
    }

    echo "\nНажмите на идентификатор голосования (<b>/номер</b>) для изменения настроек или голосования."; 
    
} else {
    
    echo "У вас пока нет голосований. Создайте новое с помощью команды /newpoll.";
    
}



