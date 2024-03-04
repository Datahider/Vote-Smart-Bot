<?php

use losthost\telle\Bot;
use losthost\patephon\handlers\CommandStart;
use losthost\patephon\handlers\CommandStartSelect;
use losthost\patephon\handlers\CommandNewPoll;
use losthost\patephon\handlers\CommandMyPolls;
use losthost\patephon\handlers\CommandPollId;

use losthost\patephon\handlers\CallbackCancel;
use losthost\patephon\handlers\CallbackVote;
use losthost\patephon\handlers\CallbackDelete;
use losthost\patephon\handlers\CallbackRefresh;

use losthost\patephon\handlers\InlineShare;

use losthost\BotView\BotView;
use losthost\patephon\data\poll;
use losthost\patephon\data\poll_item;
use losthost\patephon\data\poll_vote;

require_once 'vendor/autoload.php';
require_once 'src/functions.php';

Bot::setup();

poll::initDataStructure();
poll_item::initDataStructure();
poll_vote::initDataStructure();

BotView::setTemplateDir('src/templates');

Bot::addHandler(CommandStart::class);
Bot::addHandler(CommandStartSelect::class);
Bot::addHandler(CommandNewPoll::class);
Bot::addHandler(CommandMyPolls::class);
Bot::addHandler(CommandPollId::class);

Bot::addHandler(CallbackCancel::class);
Bot::addHandler(CallbackVote::class);
Bot::addHandler(CallbackDelete::class);
Bot::addHandler(CallbackRefresh::class);

Bot::addHandler(InlineShare::class);

Bot::run();


