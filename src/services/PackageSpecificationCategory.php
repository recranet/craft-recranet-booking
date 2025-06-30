<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use yii\base\Component;
use recranet\craftrecranetbooking\elements\PackageSpecificationCategory as PackageSpecificationCategoryElement;

/**
 * Package Specification Category service
 */
class PackageSpecificationCategory extends Component
{
    public function deleteAll() : void
    {
        $packageSpecificationCategories = PackageSpecificationCategoryElement::findAll();

        foreach ($packageSpecificationCategories as $packageSpecificationCategory) {
            try {
                Craft::$app->elements->deleteElement($packageSpecificationCategory);
            } catch (\Throwable $e) {
                Craft::error("Failed to delete package specification category with ID {$packageSpecificationCategory->id}: " . $e->getMessage(), __METHOD__);
            }
        }
    }
}
