<?php

namespace recranet\craftrecranetbooking\elements\conditions;

use craft\elements\conditions\ElementCondition;

/**
 * Accommodation Listing condition
 */
class AccommodationListingCondition extends ElementCondition
{
    protected function selectableConditionRules(): array
    {
        return array_merge(parent::selectableConditionRules(), [
            // ...
        ]);
    }
}
