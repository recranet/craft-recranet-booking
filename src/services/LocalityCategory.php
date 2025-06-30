<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use yii\base\Component;
use recranet\craftrecranetbooking\elements\LocalityCategory as LocalityCategoryElement;

/**
 * Locality Category service
 */
class LocalityCategory extends Component
{
    public function deleteAll() : void
    {
        $localityCategories = LocalityCategoryElement::findAll();

        foreach ($localityCategories as $localityCategory) {
            try {
                Craft::$app->elements->deleteElement($localityCategory);
            } catch (\Throwable $e) {
                Craft::error("Failed to delete locality category with ID {$localityCategory->id}: " . $e->getMessage(), __METHOD__);
            }
        }
    }
}
