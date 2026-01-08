<?php

namespace recranet\craftrecranetbooking\elements\conditions;

use craft\elements\conditions\ElementCondition;

/**
 * Location Category condition
 */
class LocalityCategoryCondition extends ElementCondition
{
    protected function selectableConditionRules(): array
    {
        return array_merge(parent::selectableConditionRules(), [
            // ...
        ]);
    }
}
