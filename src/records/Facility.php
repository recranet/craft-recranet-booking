<?php

namespace recranet\craftrecranetbooking\records;

use Craft;
use craft\db\ActiveRecord;

/**
 * Facility record
 */
class Facility extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%_recranet-booking_facilities}}';
    }
}
