<?php

namespace recranet\craftrecranetbooking\elements\conditions;

use Craft;
use craft\elements\conditions\ElementCondition;

/**
 * Accommodation Category condition
 */
class AccommodationCategoryCondition extends ElementCondition
{
    protected function selectableConditionRules(): array
    {
        return array_merge(parent::selectableConditionRules(), [
            // ...
        ]);
    }
}
