<?php

namespace recranet\craftrecranetbooking\records;

use Craft;
use craft\db\ActiveRecord;

/**
 * Package Specification Category record
 */
class PackageSpecificationCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%_recranet-booking_package_specification_categories}}';
    }
}
