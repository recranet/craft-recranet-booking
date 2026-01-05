<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\base\Component;
use recranet\craftrecranetbooking\elements\AccommodationCategory as AccommodationCategoryElement;

/**
 * Accommodation Category service
 */
class AccommodationCategory extends Component
{
    public function deleteAll() : void
    {
        $accommodationCategories = AccommodationCategoryElement::findAll();

        foreach ($accommodationCategories as $accommodationCategory) {
            try {
                Craft::$app->elements->deleteElement($accommodationCategory);
            } catch (\Throwable $e) {
                Craft::error("Failed to delete accommodation category with ID {$accommodationCategory->id}: " . $e->getMessage(), __METHOD__);
            }
        }
    }
}
