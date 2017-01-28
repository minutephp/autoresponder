<?php

/** @var Binding $binding */
use Minute\Event\AdminEvent;
use Minute\Event\Binding;
use Minute\Event\ListEvent;
use Minute\Event\TodoEvent;
use Minute\Lists\ListManager;
use Minute\Menu\ArMenu;
use Minute\Todo\ArTodo;

$binding->addMultiple([
    //autoresponder
    ['event' => AdminEvent::IMPORT_ADMIN_MENU_LINKS, 'handler' => [ArMenu::class, 'adminLinks']],

    //number of subs in each list for info
    ['event' => ListEvent::IMPORT_LIST_DETAILS, 'handler' => [ListManager::class, 'getSubsCount']],

    //tasks
    ['event' => TodoEvent::IMPORT_TODO_ADMIN, 'handler' => [ArTodo::class, 'getTodoList']],
]);