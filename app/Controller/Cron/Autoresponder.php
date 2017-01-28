<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 8/8/2016
 * Time: 7:56 PM
 */
namespace App\Controller\Cron {

    use Minute\Autoresponder\ArQueue;

    class Autoresponder {
        /**
         * @var ArQueue
         */
        private $arQueue;

        /**
         * QueueMails constructor.
         *
         * @param ArQueue $arQueue
         */
        public function __construct(ArQueue $arQueue) {
            $this->arQueue = $arQueue;
        }

        public function insertMails() {
            $this->arQueue->queueMails();
        }
    }
}