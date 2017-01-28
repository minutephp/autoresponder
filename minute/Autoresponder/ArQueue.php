<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 8/16/2016
 * Time: 3:24 AM
 */
namespace Minute\Autoresponder {

    use App\Model\MArCampaign;
    use App\Model\MArMessage;
    use App\Model\MArQueue;
    use App\Model\MArSummary;
    use App\Model\User;
    use Carbon\Carbon;
    use DateInterval;
    use DateTime;
    use DateTimeZone;
    use Illuminate\Support\Collection;
    use Minute\Lists\ListManager;
    use Minute\Model\CollectionEx;

    class ArQueue {
        /**
         * @var ListManager
         */
        private $listManager;

        /**
         * ArQueue constructor.
         *
         * @param ListManager $listManager
         */
        public function __construct(ListManager $listManager) {
            $this->listManager = $listManager;
        }

        public function queueMails(string $now = '') {
            MArSummary::unguard();
            MArQueue::unguard();

            $campaigns = MArCampaign::where('enabled', '=', 'true')->where('type', '=', 'autoresponder')->orderBy('priority', 'desc')->get();
            $now_time  = !empty($now) ? Carbon::parse($now) : Carbon::now();

            /** @var CollectionEx $pending_ids */
            $yesterday    = Carbon::now()->subHours(12);
            $excluded_ids = [];

            foreach (['pass', 'pending'] as $status) { //if combined into 1 query or status <> 'fail' then mysql does not use index :/ this way is 100x faster
                $pending_ids  = MArQueue::select('user_id')->distinct()->where('send_at', '>', $yesterday)->where('status', '=', $status)->get();
                $excluded_ids = array_unique(array_merge($excluded_ids, $pending_ids->pluck('user_id')->toArray())); //exclude ids which got mail less than 12 hours ago (probably from a broadcast)
            }

            foreach ($campaigns as $campaign) {
                /** @var Collection $messages */
                $ar_camp_id = $campaign->ar_campaign_id;
                //$exclusive =
                $messages = MArMessage::where('ar_campaign_id', '=', $ar_camp_id)->orderBy('sequence', 'asc')->get();
                $mail_ids = $messages->pluck('mail_id');

                $target_user_ids = $this->listManager->getTargetUserIds($campaign->ar_list_id);

                if (!empty($target_user_ids) && !empty($excluded_ids)) {
                    $target_user_ids = array_diff($target_user_ids, $excluded_ids);
                }

                if (!empty($target_user_ids)) {
                    /** @var Collection $completed - get user_ids of all users on the last mail of this Ar */
                    $six_hrs   = Carbon::parse((string) $now_time)->subHours(6);
                    $completed = MArSummary::where('ar_campaign_id', '=', $ar_camp_id)->where('last_mail_id', $mail_ids->last())->where('last_send_date', '<', $six_hrs)->select('user_id')->get();

                    if ($completed_user_ids = $completed->pluck('user_id')->all()) {
                        $target_user_ids = array_diff($target_user_ids, $completed_user_ids);
                    }

                    /** @var Collection $summaries - contains details of last mail sent by user_id */
                    $summaries = MArSummary::where('ar_campaign_id', '=', $ar_camp_id)->whereIn('user_id', $target_user_ids)->get();

                    foreach ($target_user_ids as $user_id) {
                        $mail_to_send = 0;

                        if ($summary = $summaries->where('user_id', $user_id)->first()) {
                            if (($current_mail_index = array_search($summary->last_mail_id, $mail_ids->all())) !== false) {
                                $message   = $messages->get($current_mail_index + 1);
                                $last_sent = Carbon::parse($summary->last_send_date);

                                if ($now_time > $last_sent) {
                                    $time_diff = $now_time->diffInHours($last_sent);
                                    $wait_hrs  = $message->sequence > 0 ? $message->wait_hrs : 0;

                                    if ($time_diff > $wait_hrs) {
                                        $mail_to_send = $message->mail_id;
                                    }
                                }
                            }
                        } else {
                            $summary      = new MArSummary(['user_id' => $user_id, 'ar_campaign_id' => $ar_camp_id]);
                            $mail_to_send = $mail_ids->first();
                        }

                        if ($campaign->priority > 0) { //if the Ar has priority > 0, temporarily bar this user from getting emails from other ars until this Ar is complete
                            $excluded_ids[] = $user_id;
                        }

                        if (!empty($mail_to_send)) {
                            $schedule = json_decode($campaign->schedule_json, true);

                            if (!empty($schedule)) {
                                $user      = User::find($user_id);
                                $timezone  = !empty($user->tz_offset) ? timezone_name_from_abbr("", $user->tz_offset * -60, 0) : 'America/Chicago';
                                $send_time = $this->getQueueDate($schedule, $timezone);
                            } else {
                                $send_time = $now_time;
                            }

                            try {
                                if (MArQueue::create(['send_at' => $send_time, 'user_id' => $user_id, 'mail_id' => $mail_to_send, 'status' => 'pending'])) {
                                    printf("Inserted mail %d into mail queue for user %d to send at: %s\n", $mail_to_send, $user_id, $send_time);
                                    $insertions[] = ['mail_id' => $mail_to_send, 'user_id' => $user_id];

                                    $summary->last_send_date = $send_time;
                                    $summary->last_mail_id   = $mail_to_send;
                                    $summary->save();
                                }
                            } catch (\Exception $e) {
                                echo '';
                            }
                        }
                    }
                }
            }

            return $insertions ?? [];
        }

        public function getQueueDate($ranges, $timezone = '') {
            $tz      = new DateTimeZone($timezone ?: (date_default_timezone_get() ?: 'UTC'));
            $now     = new DateTime ("now", $tz);
            $current = function ($date) {
                /** @var DateTime $date */
                $date->setTimezone(new DateTimeZone(date_default_timezone_get()));

                return $date->format("Y-m-d H:i:s");
            };

            foreach ($ranges as $range) {
                foreach ($range['days'] as $day_id => $day) {
                    $start  = DateTime::createFromFormat('D H:i', sprintf('%s %s', $day, $range['start_time']), $tz);
                    $finish = DateTime::createFromFormat('D H:i', sprintf('%s %s', $day, $range['end_time']), $tz);

                    if (($now >= $start) && ($now <= $finish)) {
                        return $current($now);
                    } elseif ($start > $now) {
                        $next = empty($next) ? $start : ($start < $next ? $start : $next);
                    } elseif (empty($next)) {
                        $next = $start->add(new DateInterval('P1W'));
                    }
                }
            }

            return !empty($next) ? $current($next) : false;
        }
    }
}