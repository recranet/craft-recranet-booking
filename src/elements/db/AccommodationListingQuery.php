<?php

namespace recranet\craftrecranetbooking\elements\db;

use craft\elements\db\ElementQuery;

/**
 * Accommodation Listing query
 */
class AccommodationListingQuery extends ElementQuery
{
    public int $recranetBookingId = 0;
    public int $organizationId = 0;

    protected function beforePrepare(): bool
    {
        $this->joinElementTable('_recranet_booking_accommodation_listings');

        if ($this->recranetBookingId) {
            $this->subQuery->andWhere(['recranetBookingId' => $this->recranetBookingId]);
        }

        if ($this->organizationId) {
            $this->subQuery->andWhere(['organizationId' => $this->organizationId]);
        }

        $this->query->select([
            '_recranet_booking_accommodation_listings.dateCreated',
            '_recranet_booking_accommodation_listings.dateUpdated',
            '_recranet_booking_accommodation_listings.recranetBookingId',
            '_recranet_booking_accommodation_listings.organizationId',
            '_recranet_booking_accommodation_listings.locale',
        ]);

        return parent::beforePrepare();
    }

    public function recranetBookingId($value): self
    {
        $this->recranetBookingId = $value;
        return $this;
    }

    public function organizationId($value): self
    {
        $this->organizationId = $value;
        return $this;
    }
}
