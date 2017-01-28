<?php
/**
 * Created by: MinutePHP Framework
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MArQueue extends ModelEx {
        protected $table      = 'm_ar_queue';
        protected $primaryKey = 'ar_queue_id';
    }
}