<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\helpers\ElementHelper;
use recranet\craftrecranetbooking\elements\Accommodation;
use recranet\craftrecranetbooking\elements\AccommodationCategory;
use recranet\craftrecranetbooking\elements\Facility;
use recranet\craftrecranetbooking\elements\LocalityCategory;
use recranet\craftrecranetbooking\elements\PackageSpecificationCategory;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\base\Component;
use recranet\craftrecranetbooking\models\Facility as FacilityModel;
use recranet\craftrecranetbooking\models\Accommodation as AccommodationModel;
use recranet\craftrecranetbooking\models\AccommodationCategory as AccommodationCategoryModel;
use recranet\craftrecranetbooking\models\LocalityCategory as LocalityCategoryModel;
use recranet\craftrecranetbooking\models\PackageSpecificationCategory as PackageSpecificationCategoryModel;

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

        $updatedFacilities = [];

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
            $updatedFacilities[] = $facilityElement->id;
        }

        $this->removeFacilities($updatedFacilities);
    }

    public function importAccommodationCategories(): void
    {
        $accommodationCategories = RecranetBooking::getInstance()->recranetBookingClient->fetchAccommodationCategories();

        if (!$accommodationCategories) {
            return;
        }

        $updatedAccommodationCategories = [];

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
            $updatedAccommodationCategories[] = $categoryElement->id;
        }

        $this->removeAccommodationCategories($updatedAccommodationCategories);
    }

    public function importLocalityCategories(): void
    {
        $localityCategories = RecranetBooking::getInstance()->recranetBookingClient->fetchLocalityCategories();

        if (!$localityCategories) {
            return;
        }

        $updatedLocalityCategories = [];

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
            $updatedLocalityCategories[] = $categoryElement->id;
        }

        $this->removeLocalityCategories($updatedLocalityCategories);
    }

    public function importPackageSpecificationCategories(): void
    {
        $packageSpecificationCategories = RecranetBooking::getInstance()->recranetBookingClient->fetchPackageSpecificationCategories();

        if (!$packageSpecificationCategories) {
            return;
        }

        $updatedPackageSpecificationCategories = [];

        foreach ($packageSpecificationCategories as $categoryData) {
            $existingCategory = PackageSpecificationCategory::find()
                ->recranetBookingId($categoryData['id'])
                ->one();

            if ($existingCategory) {
                continue;
            }

            $packageSpecificationCategory = new PackageSpecificationCategoryModel([
                'title' => $categoryData['description'],
                'recranetBookingId' => $categoryData['id'],
            ]);

            $packageSpecificationCategory->validate();

            $packageSpecificationElement = new PackageSpecificationCategory();
            $packageSpecificationElement->title = $packageSpecificationCategory['title'];
            $packageSpecificationElement->recranetBookingId = $packageSpecificationCategory['recranetBookingId'];

            Craft::$app->elements->saveElement($packageSpecificationElement);
            $updatedPackageSpecificationCategories[] = $packageSpecificationElement->id;
        }

        $this->removePackageSpecificationCategories($updatedPackageSpecificationCategories);
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
                        $existingAccommodation->titleDe = $accommodationData['title'];
                        $existingAccommodation->slugDe = $accommodationData['slug'];
                        break;
                    case 'en':
                        $existingAccommodation->titleEn = $accommodationData['title'];
                        $existingAccommodation->slugEn = $accommodationData['slug'];
                        break;
                    case 'fr':
                        $existingAccommodation->titleFr = $accommodationData['title'];
                        $existingAccommodation->slugFr = $accommodationData['slug'];
                        break;
                }

                Craft::$app->elements->saveElement($existingAccommodation);
            }
        }
    }

    private function removeFacilities(array $facilities): void
    {
        $allFacilities = Facility::find()->all();

        foreach ($allFacilities as $facility) {
            if (!in_array($facility->id, $facilities)) {
                Craft::$app->elements->deleteElement($facility);
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

    private function removeLocalityCategories(array $localityCategories): void
    {
        $allLocalityCategories = LocalityCategory::find()->all();

        foreach ($allLocalityCategories as $category) {
            if (!in_array($category->id, $localityCategories)) {
                Craft::$app->elements->deleteElement($category);
            }
        }
    }

    private function removeAccommodationCategories(array $accommodationCategories): void
    {
        $allAccommodationCategories = AccommodationCategory::find()->all();

        foreach ($allAccommodationCategories as $category) {
            if (!in_array($category->id, $accommodationCategories)) {
                Craft::$app->elements->deleteElement($category);
            }
        }
    }

    private function removePackageSpecificationCategories(array $packageSpecificationCategories): void
    {
        $allPackageSpecificationCategories = PackageSpecificationCategory::find()->all();

        foreach ($allPackageSpecificationCategories as $category) {
            if (!in_array($category->id, $packageSpecificationCategories)) {
                Craft::$app->elements->deleteElement($category);
            }
        }
    }
}
