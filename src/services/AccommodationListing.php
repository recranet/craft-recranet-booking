<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\base\Component;
use recranet\craftrecranetbooking\elements\AccommodationListing as AccommodationListingElement;

/**
 * Accommodation Listing service
 */
class AccommodationListing extends Component
{
    public function deleteAll(): void
    {
        $accommodationListings = AccommodationListingElement::findAll();

        foreach ($accommodationListings as $accommodationListing) {
            try {
                Craft::$app->elements->deleteElement($accommodationListing);
            } catch (\Throwable $e) {
                Craft::error("Failed to delete accommodation listing with ID {$accommodationListing->id}: " . $e->getMessage(), __METHOD__);
            }
        }
    }
}
