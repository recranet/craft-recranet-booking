<?php

namespace recranet\craftrecranetbooking\models;

use Craft;
use craft\base\Model;

/**
 * Organization model
 */
class Organization extends Model
{
    public int $organizationId;

    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['recranetBookingId', 'title'], 'required'],
            [['recranetBookingId'], 'integer'],
            [['title'], 'string', 'max' => 255],

        ]);
    }
}
