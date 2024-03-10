<?php

namespace losthost\polabrain\data;

use losthost\DB\DBObject;

class user extends DBObject {
    
    const METADATA = [
        'tg_user' => 'BIGINT NOT NULL',
        'last_poll' => 'BIGINT',
        'last_poll_message' => 'BIGINT',
        'PRIMARY KEY' => 'tg_user'
    ];
}
