<?php

namespace recranet\craftrecranetbooking\elements\db;

use craft\elements\db\ElementQuery;

/**
 * Facility query
 */
class FacilityQuery extends ElementQuery
{
    public int $recranetBookingId = 0;
    public int $organizationId = 0;

    protected function beforePrepare(): bool
    {
        $this->joinElementTable('_recranet-booking_facilities');

        if ($this->recranetBookingId) {
            $this->subQuery->andWhere(['recranetBookingId' => $this->recranetBookingId]);
        }

        if ($this->organizationId) {
            $this->subQuery->andWhere(['organizationId' => $this->organizationId]);
        }

        $this->query->select([
            '_recranet-booking_facilities.dateCreated',
            '_recranet-booking_facilities.dateUpdated',
            '_recranet-booking_facilities.recranetBookingId',
            '_recranet-booking_facilities.organizationId',
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
