<?php

namespace recranet\craftrecranetbooking\elements\db;

use craft\elements\db\ElementQuery;

/**
 * Organization query
 */
class OrganizationQuery extends ElementQuery
{
    public int $recranetBookingId = 0;

    protected function beforePrepare(): bool
    {
        $this->joinElementTable('_recranet_booking_organizations');

        if ($this->recranetBookingId) {
            $this->subQuery->andWhere(['recranetBookingId' => $this->recranetBookingId]);
        }

        $this->query->select([
            '_recranet_booking_organizations.dateCreated',
            '_recranet_booking_organizations.dateUpdated',
            '_recranet_booking_organizations.recranetBookingId',
            '_recranet_booking_organizations.bookPageEntry',
            '_recranet_booking_organizations.bookPageEntryTemplate',
        ]);

        return parent::beforePrepare();
    }

    public function recranetBookingId($value): self
    {
        $this->recranetBookingId = $value;
        return $this;
    }
}
