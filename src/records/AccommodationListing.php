<?php

namespace recranet\craftrecranetbooking\records;

use craft\db\ActiveRecord;

/**
 * Accommodation Listing record
 */
class AccommodationListing extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%_recranet_booking_accommodation_listings}}';
    }
}
