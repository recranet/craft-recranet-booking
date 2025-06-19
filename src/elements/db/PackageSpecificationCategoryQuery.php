<?php

namespace recranet\craftrecranetbooking\elements\db;

use Craft;
use craft\elements\db\ElementQuery;

/**
 * Package Specification Category query
 */
class PackageSpecificationCategoryQuery extends ElementQuery
{
    public int $recranetBookingId = 0;

    protected function beforePrepare(): bool
    {
        $this->joinElementTable('_recranet-booking_package_specification_categories');

        if ($this->recranetBookingId) {
            $this->subQuery->andWhere(['recranetBookingId' => $this->recranetBookingId]);
        }

        $this->query->select([
            '_recranet-booking_package_specification_categories.dateCreated',
            '_recranet-booking_package_specification_categories.dateUpdated',
            '_recranet-booking_package_specification_categories.recranetBookingId',
        ]);

        return parent::beforePrepare();
    }

    public function recranetBookingId($value): self
    {
        $this->recranetBookingId = $value;
        return $this;
    }
}
