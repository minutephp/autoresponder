<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 8/16/2016
 * Time: 3:32 AM
 */
namespace Minute\Lists {

    use App\Model\MArList;
    use App\Model\MArListSql;
    use Minute\Cache\QCache;
    use Minute\Database\Database;
    use Minute\Event\ImportEvent;

    class ListManager {
        /**
         * @var QCache
         */
        private $qCache;
        /**
         * @var Database
         */
        private $db;

        /**
         * ListManager constructor.
         *
         * @param QCache $qCache
         * @param Database $db
         */
        public function __construct(QCache $qCache, Database $db) {
            $this->qCache = $qCache;
            $this->db     = $db;
        }

        public function getTargetUserIds(int $ar_list_id): array {
            return $this->qCache->get("ar-list-id-$ar_list_id", function () use ($ar_list_id) {
                $results = ['positive' => [], 'negative' => []];

                if ($list = MArList::find($ar_list_id)) {
                    $sqls = MArListSql::where('ar_list_id', '=', $ar_list_id)->get();

                    foreach ($sqls as $sql) {
                        $users = $this->qCache->get(md5($sql->sql), function () use ($sql) {
                            foreach ($this->db->getPdo()->query($sql->sql) as $row) {
                                $results[] = $row[0];
                            }

                            return $results ?? [];
                        });

                        $results[$sql->type] = array_merge($results[$sql->type], $users);
                    }
                }

                return array_diff($results['positive'], $results['negative']) ?: [];
            }, 3600);
        }

        public function getSubsCount(ImportEvent $event) {
            $lists = MArList::all();

            foreach ($lists as $list) {
                $list_id = $list->ar_list_id;
                $subs    = $this->getTargetUserIds($list_id);

                $results[$list_id] = count($subs);
            }

            $event->setContent($results ?? []);
        }
    }
}