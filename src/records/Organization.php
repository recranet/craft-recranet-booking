<?php

namespace recranet\craftrecranetbooking\records;

use craft\db\ActiveRecord;

/**
 * Organization record
 */
class Organization extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%_recranet-booking_organizations}}';
    }
}
