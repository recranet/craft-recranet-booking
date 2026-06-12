<?php

namespace recranet\craftrecranetbooking\models;

use craft\base\Model;

/**
 * Accommodation Listing model
 */
class AccommodationListing extends Model
{
    public string $title;
    public string $locale;
    public int $recranetBookingId;
    public int $organizationId;

    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['recranetBookingId', 'organizationId', 'title', 'locale'], 'required'],
            [['recranetBookingId', 'organizationId'], 'integer'],
            [['title', 'locale'], 'string', 'max' => 255],
        ]);
    }
}
