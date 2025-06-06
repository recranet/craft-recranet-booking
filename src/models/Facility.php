<?php

namespace recranet\craftrecranetbooking\models;

use Craft;
use craft\base\Model;

/**
 * Facility model
 */
class Facility extends Model
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
