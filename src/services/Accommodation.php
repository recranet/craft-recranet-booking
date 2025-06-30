<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use yii\base\Component;
use recranet\craftrecranetbooking\elements\Accommodation as AccommodationElement;

/**
 * Accommodation service
 */
class Accommodation extends Component
{
    public function deleteAll() : void
    {
        $accommodations = AccommodationElement::findAll();

        foreach ($accommodations as $accommodation) {
            try {
                Craft::$app->elements->deleteElement($accommodation);
            } catch (\Throwable $e) {
                Craft::error("Failed to delete accommodation with ID {$accommodation->id}: " . $e->getMessage(), __METHOD__);
            }
        }
    }
}
