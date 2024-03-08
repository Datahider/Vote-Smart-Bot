<?php

namespace losthost\patephon\data;

use losthost\DB\DBObject;
use losthost\patephon\data\poll_user;
use losthost\DB\DBView;
use losthost\DB\DB;

class poll extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT NOT NULL AUTO_INCREMENT',
        'title' => 'VARCHAR(64) NOT NULL',
        'admin' => 'BIGINT NOT NULL',
        'language_code' => 'VARCHAR(10) NOT NULL DEFAULT "ru"',
        'max_rating' => 'INT NOT NULL',
        'is_free_votes' => 'TINYINT(1) NOT NULL',
        'stage' => 'ENUM("created", "ideas", "voting", "end") DEFAULT "created"',
        'is_open' => 'TINYINT(1) NOT NULL DEFAULT(0)',
        'can_block' => 'TINYINT(1) NOT NULL DEFAULT(0)',
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
    
    public function itemCount() {
        $view = new DBView("SELECT COUNT(id) as count FROM [poll_item] WHERE poll = ?", $this->id);
        $view->next();
        return $view->count;
    }
    
    public function delete($comment = '', $data = null) {

        $commit = false;
        if (!DB::inTransaction()) {
            DB::beginTransaction();
            $commit = true;
        }
        
        DB::exec("DELETE FROM [poll_vote] WHERE poll_item IN (SELECT id FROM [poll_item] WHERE poll = $this->id)");
        DB::exec("DELETE FROM [poll_item] WHERE poll = $this->id");
        DB::exec("DELETE FROM [poll_user] WHERE poll = $this->id",);
        
        parent::delete($comment, $data);
        
        if ($commit) {
            DB::commit();
        }
    }
}
