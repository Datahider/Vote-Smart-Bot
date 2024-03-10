<?php

echo "<b>$poll->title</b>\n\nДобавлены идеи:\n\n";

foreach ($ideas as $idea) {
    echo "▫️- $idea->title\n";
}


