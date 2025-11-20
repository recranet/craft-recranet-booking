<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\helpers\App;
use craft\helpers\ElementHelper;
use yii\base\Exception;
use yii\db\IntegrityException;
use recranet\craftrecranetbooking\elements\Accommodation;
use recranet\craftrecranetbooking\elements\AccommodationCategory;
use recranet\craftrecranetbooking\elements\Facility;
use recranet\craftrecranetbooking\elements\LocalityCategory;
use recranet\craftrecranetbooking\elements\Organization;
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
            $facility = Facility::find()
                ->recranetBookingId($facilityData['facilitySpecification']['id'])
                ->one();

            $facilityModel = new FacilityModel([
                'title' => $facilityData['facilitySpecification']['name'],
                'recranetBookingId' => $facilityData['facilitySpecification']['id'],
            ]);

            $facilityModel->validate();

            if (!$facility) {
                $facility = new Facility();
                $facility->recranetBookingId = $facilityModel->recranetBookingId;
            }

            $facility->title = $facilityModel->title;

            Craft::$app->elements->saveElement($facility);
            $updatedFacilities[] = $facility->id;
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
            $accommodationCategory = AccommodationCategory::find()
                ->recranetBookingId($categoryData['id'])
                ->one();

            $accommodationCategoryModel = new AccommodationCategoryModel([
                'title' => $categoryData['displayName'],
                'recranetBookingId' => $categoryData['id'],
            ]);

            $accommodationCategoryModel->validate();

            if (!$accommodationCategory) {
                $accommodationCategory = new AccommodationCategory();
                $accommodationCategory->recranetBookingId = $accommodationCategoryModel->recranetBookingId;
            }

            $accommodationCategory->title = $accommodationCategoryModel->title;

            Craft::$app->elements->saveElement($accommodationCategory);
            $updatedAccommodationCategories[] = $accommodationCategory->id;
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
            $localityCategory = LocalityCategory::find()
                ->recranetBookingId($categoryData['id'])
                ->one();

            $localityCategoryModel = new LocalityCategoryModel([
                'title' => $categoryData['displayName'],
                'recranetBookingId' => $categoryData['id'],
            ]);

            $localityCategoryModel->validate();

            if (!$localityCategory) {
                $localityCategory = new LocalityCategory();
                $localityCategory->recranetBookingId = $localityCategoryModel->recranetBookingId;
            }

            $localityCategory->title = $localityCategoryModel->title;

            Craft::$app->elements->saveElement($localityCategory);
            $updatedLocalityCategories[] = $localityCategory->id;
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
            $packageSpecificationCategory = PackageSpecificationCategory::find()
                ->recranetBookingId($categoryData['id'])
                ->one();

            $packageSpecificationCategoryModel = new PackageSpecificationCategoryModel([
                'title' => $categoryData['description'],
                'recranetBookingId' => $categoryData['id'],
            ]);

            $packageSpecificationCategoryModel->validate();

            if (!$packageSpecificationCategory) {
                $packageSpecificationCategory = new PackageSpecificationCategory();
                $packageSpecificationCategory->recranetBookingId = $packageSpecificationCategoryModel->recranetBookingId;
            }

            $packageSpecificationCategory->title = $packageSpecificationCategoryModel->title;

            Craft::$app->elements->saveElement($packageSpecificationCategory);
            $updatedPackageSpecificationCategories[] = $packageSpecificationCategory->id;
        }

        $this->removePackageSpecificationCategories($updatedPackageSpecificationCategories);
    }

    public function importAccommodations(): void
    {
        // TODO: Add foreach organization
        $organizationId = App::parseEnv(RecranetBooking::getInstance()->getSettings()->organizationId);

        $organization = Organization::find()->organizationId($organizationId)->one();
        if (!$organization) {
            $organization = new Organization();
            $organization->organizationId = $organizationId;

            Craft::$app->elements->saveElement($organization);
        }

        $accommodations = RecranetBooking::getInstance()->recranetBookingClient->fetchAccommodations('nl', $organizationId);

        if (!$accommodations) {
            return;
        }

        $updatedAccommodations = [];

        foreach ($accommodations as $accommodationData) {
            $accommodation = Accommodation::find()
                ->recranetBookingId($accommodationData['id'])
                ->one();

            // TODO add organizationId to the model
            $accommodationModel = new AccommodationModel([
                'title' => $accommodationData['title'],
                'slug' => $accommodationData['slug'] ?? ElementHelper::generateSlug($accommodationData['title']),
                'recranetBookingId' => $accommodationData['id'],
                'organizationId' => $organization->id,
            ]);

            $accommodationModel->validate();

            if (!$accommodation) {
                $accommodation = new Accommodation();
            }

            $accommodation->title = $accommodationModel->title;
            $accommodation->slug = $accommodationModel->slug;
            $accommodation->recranetBookingId = $accommodationModel->recranetBookingId;
            $accommodation->organizationId = $accommodationModel->organizationId;

            try {
                Craft::$app->elements->saveElement($accommodation);
            } catch (IntegrityException $e) {
                // Check if this is a foreign key constraint violation for organizationId
                if (str_contains($e->getMessage(), 'organizationId')) {
                    throw new Exception(
                        "Failed to save accommodation '{$accommodationModel->title}' (ID: {$accommodationModel->recranetBookingId}). " .
                        "Organization with ID {$organizationId} not found. " .
                        "Please create the organization in the Control Panel at " .
                        "/admin/recranet-booking/organizations/new before importing accommodations.",
                        0,
                        $e
                    );
                }
                // Re-throw if it's a different integrity constraint violation
                throw $e;
            }

            // I need to keep track of the imported accommodations
            $updatedAccommodations[] = $accommodation->id;
        }

        $this->importTranslatedAccommodationSlugs($organization);
        $this->removeAccommodations($updatedAccommodations);
    }

    public function importTranslatedAccommodationSlugs(Organization $organization): void
    {
        $locales = ['de', 'en', 'fr'];
        $translatedAccommodations = [];

        foreach ($locales as $locale) {
            $accommodations = RecranetBooking::getInstance()->recranetBookingClient->fetchAccommodations($locale, $organization->organizationId);

            if (!$accommodations) {
                continue;
            }

            $translatedAccommodations[$locale] = array_map(function ($accommodation) {
                return [
                    'id' => $accommodation['id'],
                    'title' => $accommodation['content']['title'],
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
