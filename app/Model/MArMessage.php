<?php
/**
 * Created by: MinutePHP Framework
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MArMessage extends ModelEx {
        protected $table      = 'm_ar_messages';
        protected $primaryKey = 'ar_message_id';
    }
}