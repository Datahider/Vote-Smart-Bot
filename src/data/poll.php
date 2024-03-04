<?php

namespace losthost\patephon\data;

use losthost\DB\DBObject;
use losthost\patephon\data\poll_user;

class poll extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT NOT NULL AUTO_INCREMENT',
        'title' => 'VARCHAR(64) NOT NULL',
        'admin' => 'BIGINT NOT NULL',
        'max_rating' => 'INT NOT NULL',
        'is_free_votes' => 'TINYINT(1) NOT NULL',
        'is_started' => 'TINYINT(1) NOT NULL', 
        'is_open' => 'TINYINT(1) NOT NULL DEFAULT(0)',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX ADMIN_NAME' => ['admin', 'title'],
    ];
    
    public function isVoteAllowed($tguser_id) {
        // TODO - сделать проверку приглашения пользователя
        if ($this->is_open) {
            return true;
        } else {
            $poll_user = new poll_user(['tg_user' => $tguser_id, 'poll' => $this->id], true);
            if ($poll_user->isNew()) {
                return false;
            }
        }
        return true;
    }
}
