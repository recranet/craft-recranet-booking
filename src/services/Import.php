<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\helpers\ElementHelper;
use recranet\craftrecranetbooking\elements\Accommodation;
use recranet\craftrecranetbooking\elements\AccommodationCategory;
use recranet\craftrecranetbooking\elements\Facility;
use recranet\craftrecranetbooking\elements\LocationCategory;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\base\Component;
use recranet\craftrecranetbooking\models\Facility as FacilityModel;
use recranet\craftrecranetbooking\models\Accommodation as AccommodationModel;
use recranet\craftrecranetbooking\models\AccommodationCategory as AccommodationCategoryModel;

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

    public function importAccommodationCategories(): void
    {
        $accommodationCategories = RecranetBooking::getInstance()->recranetBookingClient->fetchAccommodationCategories();

        if (!$accommodationCategories) {
            return;
        }

        foreach ($accommodationCategories as $categoryData) {
            $existingCategory = AccommodationCategory::find()
                ->recranetBookingId($categoryData['id'])
                ->one();

            if ($existingCategory) {
                continue;
            }

            $accommodationCategory = new AccommodationCategoryModel([
                'title' => $categoryData['displayName'],
                'recranetBookingId' => $categoryData['id'],
            ]);

            $accommodationCategory->validate();

            $categoryElement = new AccommodationCategory();
            $categoryElement->title = $accommodationCategory->title;
            $categoryElement->recranetBookingId = $accommodationCategory->recranetBookingId;

            Craft::$app->elements->saveElement($categoryElement);
        }
    }

    public function importLocationCategories(): void
    {
        $locationCategories = RecranetBooking::getInstance()->recranetBookingClient->fetchLocationCategories();

        if (!$locationCategories) {
            return;
        }

        foreach ($locationCategories as $categoryData) {
            $existingCategory = LocationCategory::find()
                ->recranetBookingId($categoryData['id'])
                ->one();

            if ($existingCategory) {
                continue;
            }

            $locationCategory = new LocationCategory([
                'title' => $categoryData['displayName'],
                'recranetBookingId' => $categoryData['id'],
            ]);

            $locationCategory->validate();

            $categoryElement = new LocationCategory();
            $categoryElement->title = $locationCategory->title;
            $categoryElement->recranetBookingId = $locationCategory->recranetBookingId;

            Craft::$app->elements->saveElement($categoryElement);
        }
    }

    public function importAccommodations(): void
    {
        $accommodations = RecranetBooking::getInstance()->recranetBookingClient->fetchAccommodations();

        if (!$accommodations) {
            return;
        }

        foreach ($accommodations as $accommodationData) {
            $existingAccommodation = Accommodation::find()
                ->recranetBookingId($accommodationData['id'])
                ->one();

            if ($existingAccommodation) {
                continue;
            }

            $accommodation = new AccommodationModel([
                'title' => $accommodationData['title'],
                'slug' => $accommodationData['slug'] ?? ElementHelper::generateSlug($accommodationData['title']),
                'recranetBookingId' => $accommodationData['id'],
            ]);

            $accommodation->validate();

            $accommodationElement = new Accommodation();
            $accommodationElement->title = $accommodation->title;
            $accommodationElement->slug = $accommodation->slug;
            $accommodationElement->recranetBookingId = $accommodation->recranetBookingId;

            Craft::$app->elements->saveElement($accommodationElement);
        }
    }
}
