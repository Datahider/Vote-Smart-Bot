<?php

$type = $poll->is_free_votes ? "Мозговой штурм" : "Опрос";
$veto = $poll->can_block ? "Да" : "Нет";
$stage = __($poll->stage);

echo <<<FIN
Тип: $type
Право вето: $veto
Этап: $stage
FIN;