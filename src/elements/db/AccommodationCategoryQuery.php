<?php

namespace recranet\craftrecranetbooking\elements\db;

use Craft;
use craft\elements\db\ElementQuery;

/**
 * Accommodation Category query
 */
class AccommodationCategoryQuery extends ElementQuery
{
    protected function beforePrepare(): bool
    {
        // todo: join the `accommodationcategories` table
        // $this->joinElementTable('accommodationcategories');

        // todo: apply any custom query params
        // ...

        return parent::beforePrepare();
    }
}
