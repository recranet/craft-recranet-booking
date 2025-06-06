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
        // todo: join the `facilities` table
        // $this->joinElementTable('facilities');

        // todo: apply any custom query params
        // ...

        return parent::beforePrepare();
    }
}
