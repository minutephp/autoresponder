<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 8/16/2016
 * Time: 3:24 AM
 */
namespace App\Controller\Cron {

    use App\Model\MArQueue;
    use Carbon\Carbon;
    use Minute\Event\Dispatcher;
    use Minute\Event\UserMailEvent;

    class SendQueue {
        /**
         * @var Dispatcher
         */
        private $dispatcher;

        /**
         * SendQueue constructor.
         *
         * @param Dispatcher $dispatcher
         */
        public function __construct(Dispatcher $dispatcher) {
            $this->dispatcher = $dispatcher;
        }

        public function sendMails() {
            if ($mails = MArQueue::where('status', '=', 'pending')->where('send_at', '<=', Carbon::now())->get()) {
                /** @var MArQueue $mail */
                foreach ($mails as $mail) {
                    $user_id = $mail->user_id;

                    if (!empty($seen[$user_id])) {
                        print "Deferring mail to user #$user_id for 1 hour\n";
                        $mail->send_at = Carbon::now()->addHours(1);
                        $mail->save();
                    } else {
                        $event = new UserMailEvent($user_id);
                        $event->setData(['mail_id', '=', $mail->mail_id]);
                        $this->dispatcher->fire(UserMailEvent::USER_SEND_EMAIL, $event);
                        $mail->status = $event->isHandled() ? 'pass' : 'fail';
                        $mail->save();

                        $seen[$user_id] = $event->isHandled();
                        print ($event->isHandled() ? "Successfully sent" : "Unable to send") . " mail to user #$user_id\n";
                    }
                }
            }
        }
    }
}