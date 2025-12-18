<?php

namespace recranet\craftrecranetbooking\elements\db;

use Craft;
use craft\elements\db\ElementQuery;

/**
 * Organization query
 */
class OrganizationQuery extends ElementQuery
{
    public int $organizationId = 0;

    protected function beforePrepare(): bool
    {
        $this->joinElementTable('_recranet-booking_organizations');

        if ($this->organizationId) {
            $this->subQuery->andWhere(['organizationId' => $this->organizationId]);
        }

        $this->query->select([
            '_recranet-booking_organizations.dateCreated',
            '_recranet-booking_organizations.dateUpdated',
            '_recranet-booking_organizations.recranetBookingId',
            '_recranet-booking_organizations.bookPageEntry',
            '_recranet-booking_organizations.bookPageEntryTemplate',
        ]);

        return parent::beforePrepare();
    }

    public function organizationId($value): self
    {
        $this->organizationId = $value;
        return $this;
    }
}
