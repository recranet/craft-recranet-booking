<?php

namespace recranet\craftrecranetbooking\elements\conditions;

use Craft;
use craft\elements\conditions\ElementCondition;

/**
 * Facility condition
 */
class FacilityCondition extends ElementCondition
{
    protected function selectableConditionRules(): array
    {
        return array_merge(parent::selectableConditionRules(), [
            // ...
        ]);
    }
}
