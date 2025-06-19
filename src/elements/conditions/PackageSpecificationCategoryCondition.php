<?php

namespace recranet\craftrecranetbooking\elements\conditions;

use Craft;
use craft\elements\conditions\ElementCondition;

/**
 * Package Specification Category condition
 */
class PackageSpecificationCategoryCondition extends ElementCondition
{
    protected function selectableConditionRules(): array
    {
        return array_merge(parent::selectableConditionRules(), [
            // ...
        ]);
    }
}
