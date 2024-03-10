<?php

use losthost\telle\Bot;
use losthost\patephon\handlers\CommandStart;
use losthost\patephon\handlers\CommandStartSelect;
use losthost\patephon\handlers\CommandStartLink;
use losthost\patephon\handlers\CommandNewPoll;
use losthost\patephon\handlers\CommandMyPolls;
use losthost\patephon\handlers\CommandPollId;

use losthost\patephon\handlers\CallbackCancel;
use losthost\patephon\handlers\CallbackVote;
use losthost\patephon\handlers\CallbackDelete;
use losthost\patephon\handlers\CallbackRefresh;
use losthost\patephon\handlers\CallbackToggle;
use losthost\patephon\handlers\CallbackStage;
use losthost\patephon\handlers\CallbackAdd;

use losthost\patephon\handlers\InlineShare;
use losthost\patephon\handlers\InlineResult;

use losthost\BotView\BotView;
use losthost\patephon\data\poll;
use losthost\patephon\data\poll_item;
use losthost\patephon\data\poll_vote;
use losthost\patephon\data\poll_user;
use losthost\patephon\data\inline_message;
use losthost\patephon\data\user;

use losthost\telle\model\DBBotParam;
use losthost\DB\DBList;
use losthost\passg\Pass;

require_once 'vendor/autoload.php';
require_once 'src/functions.php';

Bot::setup();

poll::initDataStructure();
poll_item::initDataStructure();
poll_vote::initDataStructure();
poll_user::initDataStructure();
inline_message::initDataStructure();
user::initDataStructure();

BotView::setTemplateDir('src/templates');

Bot::addHandler(CommandStart::class);
Bot::addHandler(CommandStartSelect::class);
Bot::addHandler(CommandStartLink::class);
Bot::addHandler(CommandNewPoll::class);
Bot::addHandler(CommandMyPolls::class);
Bot::addHandler(CommandPollId::class);

Bot::addHandler(CallbackCancel::class);
Bot::addHandler(CallbackVote::class);
Bot::addHandler(CallbackDelete::class);
Bot::addHandler(CallbackRefresh::class);
Bot::addHandler(CallbackToggle::class);
Bot::addHandler(CallbackStage::class);
Bot::addHandler(CallbackAdd::class);

Bot::addHandler(InlineShare::class);
Bot::addHandler(InlineResult::class);

$me = Bot::$api->getMe();
$bot_username = new DBBotParam('bot_username');
$bot_username->value = $me->getUsername();
if ($bot_username->isModified()) {
    $bot_username->write();
}

Bot::run();


