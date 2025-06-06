<?php

namespace recranet\craftrecranetbooking\elements\db;

use Craft;
use craft\elements\db\ElementQuery;

/**
 * Accommodation query
 */
class AccommodationQuery extends ElementQuery
{
    public int $recranetBookingId = 0;

    protected function beforePrepare(): bool
    {
        $this->joinElementTable('_recranet-booking_accommodations');

        if ($this->recranetBookingId) {
            $this->subQuery->andWhere(['recranetBookingId' => $this->recranetBookingId]);
        }

        $this->query->select([
            '_recranet-booking_accommodations.title',
            '_recranet-booking_accommodations.slug',
            '_recranet-booking_accommodations.dateCreated',
            '_recranet-booking_accommodations.dateUpdated',
            '_recranet-booking_accommodations.recranetBookingId',
        ]);

        return parent::beforePrepare();
    }

    public function recranetBookingId($value): self
    {
        $this->recranetBookingId = $value;
        return $this;
    }
}
