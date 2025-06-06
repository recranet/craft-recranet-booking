<?php

namespace recranet\craftrecranetbooking\records;

use Craft;
use craft\db\ActiveRecord;

/**
 * Location Category record
 */
class LocationCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%_recranet-booking_locationcategories}}';
    }
}
