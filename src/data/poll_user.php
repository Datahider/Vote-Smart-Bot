<?php

namespace losthost\polabrain\data;

use losthost\DB\DBObject;

class poll_user extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT NOT NULL AUTO_INCREMENT',
        'tg_user' => 'BIGINT NOT NULL',
        'poll' => 'BIGINT NOT NULL',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX TG_USER_POLL' => ['tg_user', 'poll']
    ];
}
