<?php

namespace losthost\patephon\data;

use losthost\DB\DBObject;

class poll_item extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT NOT NULL AUTO_INCREMENT',
        'poll' => 'BIGINT NOT NULL',
        'title' => 'VARCHAR(300)',
        'PRIMARY KEY' => 'id'
    ];
}
