<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 11/5/2016
 * Time: 11:04 AM
 */
namespace Minute\Todo {

    use App\Model\MArList;
    use App\Model\MArMessage;
    use Minute\Config\Config;
    use Minute\Event\ImportEvent;

    class ArTodo {
        /**
         * @var TodoMaker
         */
        private $todoMaker;

        /**
         * MailerTodo constructor.
         *
         * @param TodoMaker $todoMaker - This class is only called by TodoEvent (so we assume TodoMaker is be available)
         */
        public function __construct(TodoMaker $todoMaker, Config $config) {
            $this->todoMaker = $todoMaker;
        }

        public function getTodoList(ImportEvent $event) {
            $todos[] = ['name' => "Create autoresponder campaign", 'description' => 'An autoresponder campaign and at least 3 follow-up messages',
                        'status' => MArMessage::count() > 2 ? 'complete' : 'incomplete', 'link' => '/admin/autoresponder/campaigns'];
            $todos[] = ['name' => "Create mailing lists", 'description' => 'At least two different mailing list targets',
                        'status' => MArList::count() >= 2 ? 'complete' : 'incomplete', 'link' => '/admin/autoresponder/lists'];

            $event->addContent(['Autoresponder' => $todos]);
        }
    }
}