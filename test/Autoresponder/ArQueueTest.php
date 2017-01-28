<?php

namespace Test\Autoresponder {

    use Auryn\Injector;
    use Minute\Autoresponder\ArQueue;
    use Test\Unit\PHPUnit_Db_Test_Base;

    class ArQueueTest extends PHPUnit_Db_Test_Base {
        protected function setUp() {
            parent::setUp();

            $this->loadData(__DIR__ . '/../Autoresponder.sql');
        }

        public function testQueueMails() {
            /** @var ArQueue $arQueue */
            $arQueue = $this->injector->make('Minute\Autoresponder\ArQueue');

            $now        = date("Y-m-d H:i:s", strtotime('now'));
            $insertions = $arQueue->queueMails($now);
            $this->assertEquals([['mail_id' => 3, 'user_id' => 1], ['mail_id' => 3, 'user_id' => 2]], $insertions, 'Two mails inserted');

            $now        = date("Y-m-d H:i:s", strtotime('+1 hour'));
            $insertions = $arQueue->queueMails($now);
            $this->assertEquals([], $insertions, 'No mails should be inserted');

            $now        = date("Y-m-d H:i:s.0000", strtotime('+25 hour'));
            $insertions = $arQueue->queueMails($now);
            $this->assertEquals([['mail_id' => 4, 'user_id' => 1], ['mail_id' => 4, 'user_id' => 2]], $insertions, 'Two mails inserted');

            $now        = date("Y-m-d H:i:s", strtotime('+26 hour'));
            $insertions = $arQueue->queueMails($now);
            $this->assertEquals([], $insertions, 'No mails should be inserted');

            $now        = date("Y-m-d H:i:s", strtotime('+32 hour'));
            $insertions = $arQueue->queueMails($now);
            $this->assertEquals([['mail_id' => 1, 'user_id' => 1], ['mail_id' => 1, 'user_id' => 2]], $insertions, 'Mails from next autoresponder inserted');
        }
    }
}
