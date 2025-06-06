<?php

namespace recranet\craftrecranetbooking\elements\db;

use Craft;
use craft\elements\db\ElementQuery;

/**
 * Facility query
 */
class FacilityQuery extends ElementQuery
{
    protected function beforePrepare(): bool
    {
        $this->joinElementTable('_recranet-booking_facilities');

        $this->query->select([
            '_recranet-booking_facilities.dateCreated',
            '_recranet-booking_facilities.dateUpdated',
            '_recranet-booking_facilities.recranetBookingId',
        ]);

        return parent::beforePrepare();
    }
}
