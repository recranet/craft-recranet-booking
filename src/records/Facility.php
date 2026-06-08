<?php

namespace recranet\craftrecranetbooking\records;

use craft\db\ActiveRecord;

/**
 * Facility record
 */
class Facility extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%_recranet_booking_facilities}}';
    }
}
