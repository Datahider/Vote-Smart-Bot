<?php

namespace losthost\polabrain\data;

use losthost\DB\DBObject;

class inline_message extends DBObject {
    
    const METADATA = [
        'id' => 'VARCHAR(100) NOT NULL',
        'poll' => 'BIGINT NOT NULL',
        'needs_update' => 'TINYINT(1) NOT NULL',
        'PRIMARY KEY' => 'id',
        'INDEX POLL' => 'poll',
        'INDEX POLL_UPDATA' => ['needs_update','poll'],
    ];
}
