<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 8/8/2016
 * Time: 7:57 PM
 */
namespace App\Controller\Cron {

    use App\Model\MArBroadcast;
    use App\Model\MArCampaign;
    use App\Model\MArMessage;
    use App\Model\MArQueue;
    use Carbon\Carbon;
    use Minute\Lists\ListManager;

    class QueueBroadcast {
        /**
         * @var ListManager
         */
        private $listManager;

        /**
         * QueueBroadcast constructor.
         *
         * @param ListManager $listManager
         */
        public function __construct(ListManager $listManager) {
            $this->listManager = $listManager;
        }

        public function insertBroadcast() {
            if ($broadcasts = MArBroadcast::where('status', '=', 'queued')->where('send_at', '<=', Carbon::now())->get()) {
                /** @var MArBroadcast $broadcast */
                foreach ($broadcasts as $broadcast) {
                    $broadcast->status = 'processing';
                    $broadcast->save();

                    $status = 'fail';

                    if ($campaign = MArCampaign::where('ar_campaign_id', '=', $broadcast->ar_campaign_id)->where('enabled', '=', 'true')->first()) {
                        MArQueue::unguard();

                        /** @var Carbon $last */
                        $last     = null;
                        $mails    = MArMessage::where('ar_campaign_id', '=', $campaign->ar_campaign_id)->orderBy('sequence', 'asc')->get();
                        $user_ids = $this->listManager->getTargetUserIds($campaign->ar_list_id);

                        if (!empty($user_ids)) {
                            $max_mail_time = ($broadcast->mailing_time ?: 1) * 60 * 60;
                            $mails_per_sec = min(1, $max_mail_time / count($user_ids));

                            foreach ($mails as $mail) {
                                $time_offset = 0;;
                                $wait = !empty($last) ? $last->addHours($mail->wait_hrs ?: 24)->getTimestamp() : time();

                                foreach ($user_ids as $user_id) {
                                    try {
                                        $send = Carbon::createFromTimestamp($wait + floor($time_offset));

                                        if (MArQueue::create(['user_id' => $user_id, 'mail_id' => $mail->mail_id, 'send_at' => $send, 'status' => 'pending'])) {
                                            echo "Mail #$mail->mail_id for #$user_id sent ", $send->diffForHumans() . "\n";
                                            $time_offset += $mails_per_sec;
                                        }
                                    } catch (\Exception $e) {
                                        //probably duplicate insert (because laravel doesn't have INSERT IGNORE)
                                    }
                                }

                                $last = $send ?? Carbon::now();
                            }
                        }

                        $status = 'sent';
                    }

                    $broadcast->status = $status;
                    $broadcast->save();
                }
            }
        }
    }
}