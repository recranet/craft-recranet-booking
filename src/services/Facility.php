<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\base\Component;
use recranet\craftrecranetbooking\elements\Facility as FacilityElement;

/**
 * Facility service
 */
class Facility extends Component
{
    public function deleteAll() : void
    {
        $facilities = FacilityElement::findAll();

        foreach ($facilities as $facility) {
            try {
                Craft::$app->elements->deleteElement($facility);
            } catch (\Throwable $e) {
                Craft::error("Failed to delete facility with ID {$facility->id}: " . $e->getMessage(), __METHOD__);
            }
        }
    }
}
