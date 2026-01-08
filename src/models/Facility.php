<?php

namespace recranet\craftrecranetbooking\models;

use craft\base\Model;

/**
 * Facility model
 */
class Facility extends Model
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
