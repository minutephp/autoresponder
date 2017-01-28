<?php

use Phinx\Migration\AbstractMigration;

class AutoresponderSeedData extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
		$this->execute('insert ignore into `m_ar_lists` (`created_at`, `updated_at`, `name`, `description`) values (NOW(), NOW(), \'All users\', \'Send to all users\')');
		$this->execute('insert ignore into `m_ar_list_sqls` (`ar_list_id`, `name`, `sql`, `type`) values ((select ar_list_id from m_ar_lists where name = "All users" limit 1), \'everyone\', \'SELECT user_id from USERS WHERE 1\', \'positive\')');
		$this->execute('insert ignore into `m_ar_lists` (`created_at`, `updated_at`, `name`, `description`) values (NOW(), NOW(), \'Paid users\', \'Paid users only\')');
		$this->execute('insert ignore into `m_ar_list_sqls` (`ar_list_id`, `name`, `sql`, `type`) values ((select ar_list_id from m_ar_lists where name = "Paid users" limit 1), \'active power, business accounts only\', \'SELECT user_id from users WHERE user_id IN (select user_id from m_user_groups WHERE group_name in (\\\'business\\\', \\\'power\\\') and expires_at > NOW())\', \'positive\')');
		$this->execute('insert ignore into `m_ar_lists` (`created_at`, `updated_at`, `name`, `description`) values (NOW(), NOW(), \'Free users\', \'Paid users only\')');
		$this->execute('insert ignore into `m_ar_list_sqls` (`ar_list_id`, `name`, `sql`, `type`) values ((select ar_list_id from m_ar_lists where name = "Free users" limit 1), \'all users\', \'SELECT user_id from users WHERE 1\', \'positive\')');
		$this->execute('insert ignore into `m_ar_list_sqls` (`ar_list_id`, `name`, `sql`, `type`) values ((select ar_list_id from m_ar_lists where name = "Free users" limit 1), \'power and business account users\', \'SELECT user_id from users WHERE user_id IN (select user_id from m_user_groups WHERE group_name in (\\\'business\\\', \\\'power\\\') and expires_at > NOW())\', \'negative\')');


		$this->execute('insert ignore into `m_cron_jobs` (`cron_job_id`, `created_at`, `updated_at`, `name`, `description`, `type`, `path`, `schedules_json`, `output_to`, `jitter`, `arguments`, `advanced`, `enabled`, `running`) values (\'1\', NOW(), NOW(), \'run-ar\', \'queue emails from autoresponder\', \'action\', \'App\\\\Controller\\\\Cron\\\\Autoresponder@insertMails\', \'[{\\"min\\":\\"*/5\\",\\"hour\\":\\"*\\",\\"daymonth\\":\\"*\\",\\"month\\":\\"*\\",\\"dayweek\\":\\"*\\"}]\', null, null, null, \'false\', \'true\', \'false\')');
		$this->execute('insert ignore into `m_cron_jobs` (`cron_job_id`, `created_at`, `updated_at`, `name`, `description`, `type`, `path`, `schedules_json`, `output_to`, `jitter`, `arguments`, `advanced`, `enabled`, `running`) values (\'2\', NOW(), NOW(), \'send-mail-queue\', \'send emails from mail queue\', \'action\', \'App\\\\Controller\\\\Cron\\\\SendQueue@sendMails\', \'[{\\"min\\":\\"*/5\\",\\"hour\\":\\"*\\",\\"daymonth\\":\\"*\\",\\"month\\":\\"*\\",\\"dayweek\\":\\"*\\"}]\', null, null, null, \'false\', \'true\', \'false\')');
		$this->execute('insert ignore into `m_cron_jobs` (`cron_job_id`, `created_at`, `updated_at`, `name`, `description`, `type`, `path`, `schedules_json`, `output_to`, `jitter`, `arguments`, `advanced`, `enabled`, `running`) values (\'3\', NOW(), NOW(), \'Send broadcast\', \'insert broadcast\', \'action\', \'App\\\\Controller\\\\Cron\\\\QueueBroadcast@insertBroadcast\', \'[{\\"min\\":\\"*/5\\",\\"hour\\":\\"*\\",\\"daymonth\\":\\"*\\",\\"month\\":\\"*\\",\\"dayweek\\":\\"*\\"}]\', null, null, null, \'false\', \'true\', \'false\')');


    }
}