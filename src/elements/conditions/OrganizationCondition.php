<?php

namespace recranet\craftrecranetbooking\elements\conditions;

use craft\elements\conditions\ElementCondition;

/**
 * Organization condition
 */
class OrganizationCondition extends ElementCondition
{
    protected function selectableConditionRules(): array
    {
        return array_merge(parent::selectableConditionRules(), [
            // ...
        ]);
    }
}
