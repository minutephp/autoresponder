<?php

namespace Test\Lists {

    use Auryn\Injector;
    use Minute\Lists\ListManager;
    use Test\Unit\PHPUnit_Db_Test_Base;

    class ListManagerTest extends PHPUnit_Db_Test_Base {

        public function testGetTargetUserIds() {
            /** @var ListManager $listManager */
            $listManager = (new Injector())->make('Minute\Lists\ListManager');

            $userIds = $listManager->getTargetUserIds(1);
            $this->assertEquals([1, 2], $userIds, 'Userids match');

            $userIds = $listManager->getTargetUserIds(2);
            $this->assertEquals([], $userIds, 'Userids match');
        }

        protected function getDataSet() {
            return $this->loadData(__DIR__ . '/../Autoresponder.sql');
        }
    }
}