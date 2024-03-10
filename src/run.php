<?php

use losthost\telle\Bot;
use losthost\polabrain\handlers\CommandStart;
use losthost\polabrain\handlers\CommandStartSelect;
use losthost\polabrain\handlers\CommandStartLink;
use losthost\polabrain\handlers\CommandNewPoll;
use losthost\polabrain\handlers\CommandMyPolls;
use losthost\polabrain\handlers\CommandPollId;
use losthost\polabrain\handlers\GeneralMessage;

use losthost\polabrain\handlers\CallbackCancel;
use losthost\polabrain\handlers\CallbackVote;
use losthost\polabrain\handlers\CallbackDelete;
use losthost\polabrain\handlers\CallbackRefresh;
use losthost\polabrain\handlers\CallbackToggle;
use losthost\polabrain\handlers\CallbackStage;
use losthost\polabrain\handlers\CallbackAdd;

use losthost\polabrain\handlers\InlineShare;
use losthost\polabrain\handlers\InlineResult;

use losthost\BotView\BotView;
use losthost\polabrain\data\poll;
use losthost\polabrain\data\poll_item;
use losthost\polabrain\data\poll_vote;
use losthost\polabrain\data\poll_user;
use losthost\polabrain\data\inline_message;
use losthost\polabrain\data\user;

use losthost\telle\model\DBBotParam;

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

Bot::addHandler(CommandNewPoll::class);
Bot::addHandler(CommandStart::class);
Bot::addHandler(CommandStartSelect::class);
Bot::addHandler(CommandStartLink::class);
Bot::addHandler(CommandMyPolls::class);
Bot::addHandler(CommandPollId::class);
Bot::addHandler(GeneralMessage::class);

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

$cron_sleep_time = new DBBotParam('cron_sleep_time', 1);
$cron_sleep_time->value = 1;
if ($cron_sleep_time->isModified()) {
    $cron_sleep_time->write();
}

Bot::run();


