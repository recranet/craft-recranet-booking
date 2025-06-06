<?php

namespace recranet\craftrecranetbooking\records;

use Craft;
use craft\db\ActiveRecord;

/**
 * Accommodation Category record
 */
class AccommodationCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%_recranet-booking_accommodationcategories}}';
    }
}
