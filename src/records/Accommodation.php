<?php

namespace recranet\craftrecranetbooking\records;

use craft\db\ActiveRecord;

/**
 * Accommodation record
 */
class Accommodation extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%_recranet-booking_accommodations}}';
    }
}
