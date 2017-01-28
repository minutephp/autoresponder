<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class AutoresponderInitialMigration extends AbstractMigration
{
    public function change()
    {
        // Automatically created phinx migration commands for tables from database minute

        // Migration for table m_ar_broadcasts
        $table = $this->table('m_ar_broadcasts', array('id' => 'ar_broadcast_id'));
        $table
            ->addColumn('ar_campaign_id', 'integer', array('limit' => 11))
            ->addColumn('send_at', 'datetime', array())
            ->addColumn('mailing_time', 'integer', array('default' => '1', 'limit' => 11))
            ->addColumn('status', 'enum', array('default' => 'draft', 'values' => array('draft','queued','processing','sent')))
            ->addIndex(array('ar_campaign_id'), array('unique' => true))
            ->addIndex(array('status', 'send_at'), array())
            ->create();


        // Migration for table m_ar_campaigns
        $table = $this->table('m_ar_campaigns', array('id' => 'ar_campaign_id'));
        $table
            ->addColumn('created_at', 'datetime', array())
            ->addColumn('type', 'enum', array('default' => 'autoresponder', 'values' => array('autoresponder','broadcast')))
            ->addColumn('name', 'string', array('limit' => 255))
            ->addColumn('description', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('ar_list_id', 'integer', array('null' => true, 'limit' => 11))
            ->addColumn('schedule_json', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addColumn('priority', 'integer', array('null' => true, 'default' => '0', 'limit' => 11))
            ->addColumn('advanced', 'enum', array('null' => true, 'default' => 'false', 'values' => array('false','true')))
            ->addColumn('enabled', 'enum', array('default' => 'false', 'values' => array('false','true')))
            ->addIndex(array('ar_list_id'), array())
            ->create();


        // Migration for table m_ar_list_sqls
        $table = $this->table('m_ar_list_sqls', array('id' => 'ar_list_sql_id'));
        $table
            ->addColumn('ar_list_id', 'integer', array('limit' => 11))
            ->addColumn('name', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('sql', 'text', array('limit' => MysqlAdapter::TEXT_LONG))
            ->addColumn('type', 'enum', array('default' => 'positive', 'values' => array('positive','negative')))
            ->addIndex(array('ar_list_id', 'name'), array('unique' => true))
            ->addIndex(array('ar_list_id'), array())
            ->create();


        // Migration for table m_ar_lists
        $table = $this->table('m_ar_lists', array('id' => 'ar_list_id'));
        $table
            ->addColumn('created_at', 'datetime', array())
            ->addColumn('updated_at', 'datetime', array())
            ->addColumn('name', 'string', array('limit' => 255))
            ->addColumn('description', 'string', array('null' => true, 'limit' => 255))
            ->addIndex(array('name'), array('unique' => true))
            ->create();


        // Migration for table m_ar_messages
        $table = $this->table('m_ar_messages', array('id' => 'ar_message_id'));
        $table
            ->addColumn('ar_campaign_id', 'integer', array('limit' => 11))
            ->addColumn('updated_at', 'datetime', array())
            ->addColumn('mail_id', 'integer', array('limit' => 11))
            ->addColumn('sequence', 'integer', array('null' => true, 'limit' => 11))
            ->addColumn('wait_hrs', 'integer', array('default' => '1', 'limit' => 11))
            ->addIndex(array('ar_campaign_id', 'mail_id'), array('unique' => true))
            ->create();


        // Migration for table m_ar_queue
        $table = $this->table('m_ar_queue', array('id' => 'ar_queue_id'));
        $table
            ->addColumn('user_id', 'integer', array('limit' => 11))
            ->addColumn('mail_id', 'integer', array('limit' => 11))
            ->addColumn('send_at', 'datetime', array())
            ->addColumn('status', 'enum', array('default' => 'pending', 'values' => array('pending','pass','fail')))
            ->addIndex(array('user_id', 'mail_id'), array('unique' => true))
            ->addIndex(array('send_at', 'status'), array())
            ->create();


        // Migration for table m_ar_summary
        $table = $this->table('m_ar_summary', array('id' => 'ar_summary_id'));
        $table
            ->addColumn('user_id', 'integer', array('limit' => 11))
            ->addColumn('ar_campaign_id', 'integer', array('limit' => 11))
            ->addColumn('last_send_date', 'datetime', array())
            ->addColumn('last_mail_id', 'integer', array('limit' => 11))
            ->addIndex(array('user_id', 'ar_campaign_id'), array('unique' => true))
            ->addIndex(array('ar_campaign_id', 'last_mail_id'), array())
            ->create();


    }
}