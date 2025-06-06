<?php

namespace recranet\craftrecranetbooking\elements\db;

use Craft;
use craft\elements\db\ElementQuery;

/**
 * Location Category query
 */
class LocationCategoryQuery extends ElementQuery
{
    protected function beforePrepare(): bool
    {
        // todo: join the `locationcategories` table
        // $this->joinElementTable('locationcategories');

        // todo: apply any custom query params
        // ...

        return parent::beforePrepare();
    }
}
