<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 7/8/2016
 * Time: 7:57 PM
 */
namespace Minute\Menu {

    use Minute\Event\ImportEvent;

    class ArMenu {
        public function adminLinks(ImportEvent $event) {
            $links = [
                'autoresponder' => ['title' => 'Auto responders', 'icon' => 'fa-retweet', 'priority' => 2, 'parent' => 'mails', 'href' => '/admin/autoresponder/campaigns'],
                'broadcast' => ['title' => 'Broadcasts', 'icon' => 'fa-bullhorn', 'priority' => 3, 'parent' => 'mails', 'href' => '/admin/autoresponder/broadcast'],
                'mailingList' => ['title' => 'Mailing Lists', 'icon' => 'fa-list', 'priority' => 4, 'parent' => 'mails', 'href' => '/admin/autoresponder/lists'],
            ];

            $event->addContent($links);
        }
    }
}