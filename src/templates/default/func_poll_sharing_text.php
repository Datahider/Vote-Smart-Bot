<?php
if (empty($poll_results)) {
    $results_text = '<i>Участники пока не предложили ни одного варианта.</i>';
} elseif ($poll->stage == 'created') {
    $results_text = $poll->is_free_votes ? 'Мозговой штурм ещё не начат.' : 'Опрос ещё не начат.';
} else {
    $results_text = '';
    foreach ($poll_results as $result) {
        if ($poll->stage == 'voting' || $poll->stage == 'end') {
            $results_text .= "$result[icon] - $result[title] [$result[rating]]\n";
        } elseif ($poll->stage == 'ideas') {
            $results_text .= "$result[icon] - $result[title]\n";
        }
    }
}
?><?=$poll->is_free_votes ? 'Мозговой штурм' : 'Опрос';?>

<b><?=$poll->title;?></b> (<?=__($poll->stage, $poll->language_code);?>)

<?=$results_text;?>