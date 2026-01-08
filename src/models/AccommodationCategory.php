<?php

namespace recranet\craftrecranetbooking\models;

use craft\base\Model;

/**
 * Accommodation Category model
 */
class AccommodationCategory extends Model
{
    public string $title;
    public int $recranetBookingId;
    public int $organizationId;

    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['recranetBookingId', 'organizationId', 'title'], 'required'],
            [['recranetBookingId', 'organizationId'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ]);
    }
}
