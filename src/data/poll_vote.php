<?php

namespace losthost\patephon\data;

use losthost\DB\DBObject;

class poll_vote extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT NOT NULL AUTO_INCREMENT',
        'poll_item' => 'BIGINT NOT NULL',
        'tg_user' => 'BIGINT NOT NULL',
        'vote' => 'INT',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX ITEM_USER' => ['tg_user', 'poll_item']
    ];
}
