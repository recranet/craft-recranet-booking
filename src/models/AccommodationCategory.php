<?php

namespace recranet\craftrecranetbooking\models;

use Craft;
use craft\base\Model;

/**
 * Accommodation Category model
 */
class AccommodationCategory extends Model
{
    public string $title;
    public int $recranetBookingId;

    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['recranetBookingId', 'title'], 'required'],
            [['recranetBookingId'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ]);
    }
}
