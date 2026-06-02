<?php

namespace recranet\craftrecranetbooking\records;

use craft\db\ActiveRecord;

/**
 * Location Category record
 */
class LocalityCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%_recranet_booking_locality_categories}}';
    }
}
