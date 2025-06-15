<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\helpers\ElementHelper;
use recranet\craftrecranetbooking\elements\Accommodation;
use recranet\craftrecranetbooking\elements\AccommodationCategory;
use recranet\craftrecranetbooking\elements\Facility;
use recranet\craftrecranetbooking\elements\LocalityCategory;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\base\Component;
use recranet\craftrecranetbooking\models\Facility as FacilityModel;
use recranet\craftrecranetbooking\models\Accommodation as AccommodationModel;
use recranet\craftrecranetbooking\models\AccommodationCategory as AccommodationCategoryModel;
use recranet\craftrecranetbooking\models\LocalityCategory as LocalityCategoryModel;

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

    public function importLocalityCategories(): void
    {
        $localityCategories = RecranetBooking::getInstance()->recranetBookingClient->fetchLocalityCategories();

        if (!$localityCategories) {
            return;
        }

        foreach ($localityCategories as $categoryData) {
            $existingCategory = LocalityCategory::find()
                ->recranetBookingId($categoryData['id'])
                ->one();

            if ($existingCategory) {
                continue;
            }

            $localityCategory = new LocalityCategoryModel([
                'title' => $categoryData['displayName'],
                'recranetBookingId' => $categoryData['id'],
            ]);

            $localityCategory->validate();

            $categoryElement = new LocalityCategory();
            $categoryElement->title = $localityCategory->title;
            $categoryElement->recranetBookingId = $localityCategory->recranetBookingId;

            Craft::$app->elements->saveElement($categoryElement);
        }
    }

    public function importAccommodations(): void
    {
        $accommodations = RecranetBooking::getInstance()->recranetBookingClient->fetchAccommodations('nl');

        if (!$accommodations) {
            return;
        }

        $updatedAccommodations = [];

        foreach ($accommodations as $accommodationData) {
            $accommodation = Accommodation::find()
                ->recranetBookingId($accommodationData['id'])
                ->one();

            $accommodationModel = new AccommodationModel([
                'title' => $accommodationData['title'],
                'slug' => $accommodationData['slug'] ?? ElementHelper::generateSlug($accommodationData['title']),
                'recranetBookingId' => $accommodationData['id'],
            ]);

            $accommodationModel->validate();

            if (!$accommodation) {
                $accommodation = new Accommodation();
            }

            $accommodation->title = $accommodationModel->title;
            $accommodation->slug = $accommodationModel->slug;
            $accommodation->recranetBookingId = $accommodationModel->recranetBookingId;

            Craft::$app->elements->saveElement($accommodation);

            // I need to keep track of the imported accommodations
            $updatedAccommodations[] = $accommodation->id;
        }

        $this->importTranslatedAccommodationSlugs();
        $this->removeAccommodations($updatedAccommodations);
    }

    public function importTranslatedAccommodationSlugs(): void
    {
        $locales = ['de', 'en', 'fr'];
        $translatedAccommodations = [];

        foreach ($locales as $locale) {
            $accommodations = RecranetBooking::getInstance()->recranetBookingClient->fetchAccommodations($locale);

            if (!$accommodations) {
                continue;
            }

            $translatedAccommodations[$locale] = array_map(function ($accommodation) {
                return [
                    'id' => $accommodation['id'],
                    'title' => $accommodation['title'],
                    'slug' => $accommodation['slug'] ?? ElementHelper::generateSlug($accommodation['title']),
                ];
            }, $accommodations);
        }

        foreach ($translatedAccommodations as $locale => $accommodations) {
            foreach ($accommodations as $accommodationData) {
                $existingAccommodation = Accommodation::find()
                    ->recranetBookingId($accommodationData['id'])
                    ->one();

                if (!$existingAccommodation) {
                    continue;
                }

                switch ($locale) {
                    case 'de':
                        $existingAccommodation->slugDe = $accommodationData['slug'];
                        break;
                    case 'en':
                        $existingAccommodation->slugEn = $accommodationData['slug'];
                        break;
                    case 'fr':
                        $existingAccommodation->slugFr = $accommodationData['slug'];
                        break;
                }

                Craft::$app->elements->saveElement($existingAccommodation);
            }
        }
    }

    private function removeAccommodations(array $accommodations): void
    {
        $allAccommodations = Accommodation::find()->all();

        foreach ($allAccommodations as $accommodation) {
            if (!in_array($accommodation->id, $accommodations)) {
                Craft::$app->elements->deleteElement($accommodation);
            }
        }
    }
}
