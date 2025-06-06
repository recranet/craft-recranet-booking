<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use recranet\craftrecranetbooking\elements\Facility;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\base\Component;
use recranet\craftrecranetbooking\models\Facility as FacilityModel;

/**
 * Import service
 */
class Import extends Component
{
    public function importFacilities(): void
    {
        $facilities = RecranetBooking::getInstance()->recranetBookingClient->fetchFacilities();

        if (!$facilities) {
            return;
        }

        foreach ($facilities as $facilityData) {
            $existingFacility = Facility::find()
                ->recranetBookingId($facilityData['facilitySpecification']['id'])
                ->one();

            if ($existingFacility) {
                continue;
            }

            $facility = new FacilityModel([
                'title' => $facilityData['facilitySpecification']['name'],
                'recranetBookingId' => $facilityData['facilitySpecification']['id'],
            ]);

            $facility->validate();

            $facilityElement = new Facility();
            $facilityElement->title = $facility->title;
            $facilityElement->recranetBookingId = $facility->recranetBookingId;

            Craft::$app->elements->saveElement($facilityElement);
        }
    }
}
