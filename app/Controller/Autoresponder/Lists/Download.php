<?php
/**
 * Created by: MinutePHP framework
 */
namespace App\Controller\Autoresponder\Lists {

    use App\Model\User;
    use Minute\Http\HttpResponseEx;
    use Minute\Lists\ListManager;

    class Download {
        /**
         * @var ListManager
         */
        private $listManager;
        /**
         * @var HttpResponseEx
         */
        private $response;

        /**
         * Download constructor.
         *
         * @param ListManager $listManager
         * @param HttpResponseEx $response
         */
        public function __construct(ListManager $listManager, HttpResponseEx $response) {
            $this->listManager = $listManager;
            $this->response    = $response;
        }

        public function index(int $ar_list_id) {
            $user_ids = $this->listManager->getTargetUserIds($ar_list_id);
            $out      = fopen('php://output', 'w');

            $this->response->asFile(sprintf('ar_download_list_%d.csv', $ar_list_id), 'text/csv');

            /** @var User $user */
            foreach (User::find($user_ids ?? [0]) as $user) {
                $attrs = $user->attributesToArray();
                unset($attrs['password']);
                unset($attrs['ident']);

                if (empty($header)) {
                    $header = true;
                    fputcsv($out, array_keys($attrs));
                }

                fputcsv($out, $attrs);
            }

            fclose($out);
        }
    }
}