<?php

namespace recranet\craftrecranetbooking\elements\conditions;

use craft\elements\conditions\ElementCondition;

/**
 * Accommodation condition
 */
class AccommodationCondition extends ElementCondition
{
    protected function selectableConditionRules(): array
    {
        return array_merge(parent::selectableConditionRules(), [
            // ...
        ]);
    }
}
