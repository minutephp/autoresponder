<?php

/** @var Router $router */
use Minute\Routing\Router;

$router->get('/admin/autoresponder/campaigns', null, 'admin', 'm_ar_campaigns[5] as campaigns ORDER BY created_at DESC', 'm_ar_lists[campaigns.ar_list_id] as list',
    'm_ar_messages[campaigns.ar_campaign_id][1] as messages')
       ->addConstraint('campaigns', ['type', '=', 'autoresponder'])->setReadPermission('campaigns', 'admin')->setDefault('campaigns', '*');
$router->post('/admin/autoresponder/campaigns', null, 'admin', 'm_ar_campaigns as campaigns', 'm_ar_messages as messages')
       ->setAllPermissions('campaigns', 'admin')->setAllPermissions('messages', 'admin');

$router->get('/admin/autoresponder/campaigns/edit/{ar_campaign_id}', null, 'admin', 'm_ar_campaigns[ar_campaign_id][1] as campaigns',
    'm_ar_messages[campaigns.ar_campaign_id][9] as messages', 'm_mails[messages.mail_id] as mail', 'm_ar_lists[campaigns.ar_list_id] as list', 'm_ar_broadcasts[campaigns.ar_campaign_id][1] as broadcasts',
    'm_mails[5] as all_mails', 'm_ar_lists[5] as all_lists')
       ->setDefault('all_mails', '*')->setDefault('all_lists', '*')->setDefault('ar_campaign_id', 0)
       ->setReadPermission('campaigns', 'admin')->setReadPermission('all_mails', 'admin')->setReadPermission('all_lists', 'admin');
$router->post('/admin/autoresponder/campaigns/edit/{ar_campaign_id}', null, 'admin', 'm_ar_campaigns as campaigns', 'm_ar_messages as messages', 'm_ar_broadcasts as broadcasts')
       ->setAllPermissions('campaigns', 'admin')->setAllPermissions('messages', 'admin')->setAllPermissions('broadcasts', 'admin')->setDefault('ar_campaign_id', 0);

$router->get('/admin/autoresponder/lists', null, 'admin', 'm_ar_lists[5] as lists ORDER BY created_at DESC', 'm_ar_list_sqls[lists.ar_list_id][1] as sqls')
       ->setReadPermission('lists', 'admin')->setDefault('lists', '*');
$router->post('/admin/autoresponder/lists', null, 'admin', 'm_ar_lists as lists', 'm_ar_list_sqls as sqls')
       ->setAllPermissions('lists', 'admin')->setAllPermissions('sqls', 'admin')->setDeleteCascade('lists', 'sqls');

$router->get('/admin/autoresponder/lists/edit/{ar_list_id}', null, 'admin', 'm_ar_lists[ar_list_id][1] as lists', 'm_ar_list_sqls[lists.ar_list_id][1] as sqls')
       ->setReadPermission('lists', 'admin')->setDefault('ar_list_id', '0');
$router->post('/admin/autoresponder/lists/edit/{ar_list_id}', null, 'admin', 'm_ar_lists as lists', 'm_ar_list_sqls as sqls')
       ->setDefault('ar_list_id', '0')->setAllPermissions('lists', 'admin')->setAllPermissions('sqls', 'admin');

$router->get('/admin/autoresponder/lists/download/{ar_list_id}', 'Autoresponder/Lists/Download', 'admin')
       ->setDefault('_noView', true);

$router->get('/admin/autoresponder/broadcast', null, 'admin', 'm_ar_campaigns[5] as campaigns', 'm_ar_lists[campaigns.ar_list_id] as list', 'm_ar_messages[campaigns.ar_campaign_id][1] as messages',
    'm_ar_broadcasts[campaigns.ar_campaign_id] as broadcast')->addConstraint('campaigns', ['type', '=', 'broadcast'])
       ->setReadPermission('campaigns', 'admin')->setDefault('campaigns', '*');
$router->post('/admin/autoresponder/broadcast', null, 'admin', 'm_ar_campaigns as campaigns', 'm_ar_messages as messages', 'm_ar_broadcasts as broadcast')
       ->setAllPermissions('campaigns', 'admin')->setAllPermissions('messages', 'admin')->setAllPermissions('broadcast', 'admin')->setDeleteCascade('campaigns', 'm_ar_messages');
