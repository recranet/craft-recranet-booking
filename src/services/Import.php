<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\base\Component;
use craft\base\Element;
use craft\base\Model;
use craft\helpers\ElementHelper;
use recranet\craftrecranetbooking\elements\Accommodation;
use recranet\craftrecranetbooking\elements\AccommodationCategory;
use recranet\craftrecranetbooking\elements\Facility;
use recranet\craftrecranetbooking\elements\LocalityCategory;
use recranet\craftrecranetbooking\elements\Organization;
use recranet\craftrecranetbooking\elements\PackageSpecificationCategory;
use recranet\craftrecranetbooking\RecranetBooking;
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
    public function importAccommodations(Organization $organization): void
    {
        $accommodations = RecranetBooking::getInstance()->recranetBookingClient->fetchAccommodations('nl', $organization);

        if (!$accommodations) {
            return;
        }

        $updatedAccommodations = [];

        foreach ($accommodations as $data) {
            $accommodationModel = new AccommodationModel([
                'title' => $data['title'],
                'slug' => $data['slug'] ?? ElementHelper::generateSlug($data['title']),
                'recranetBookingId' => $data['id'],
                'organizationId' => $organization->getId(),
            ]);

            $accommodationModel->validate();

            $accommodation = $this->findOrCreateElement($data['id'], $organization, $accommodationModel, Accommodation::class);
            $accommodation->slug = $accommodationModel->slug;

            Craft::$app->elements->saveElement($accommodation);

            $updatedAccommodations[] = $accommodation->id;
        }

        $this->importTranslatedAccommodationSlugs($organization);

        $this->removeStaleEntities($organization, $updatedAccommodations, Accommodation::class);
    }

    public function importAccommodationCategories(Organization $organization): void
    {
        $accommodationCategories = RecranetBooking::getInstance()->recranetBookingClient->fetchAccommodationCategories($organization);

        if (!$accommodationCategories) {
            return;
        }

        $updatedAccommodationCategories = [];

        foreach ($accommodationCategories as $data) {
            $accommodationCategoryModel = new AccommodationCategoryModel([
                'title' => $data['displayName'],
                'recranetBookingId' => $data['id'],
                'organizationId' => $organization->getId(),
            ]);

            $accommodationCategoryModel->validate();

            $accommodationCategory = $this->findOrCreateElement($data['id'], $organization, $accommodationCategoryModel, AccommodationCategory::class);

            Craft::$app->elements->saveElement($accommodationCategory);
            $updatedAccommodationCategories[] = $accommodationCategory->id;
        }

        $this->removeStaleEntities($organization, $updatedAccommodationCategories, AccommodationCategory::class);
    }

    public function importFacilities(Organization $organization): void
    {
        $facilities = RecranetBooking::getInstance()->recranetBookingClient->fetchFacilities($organization);

        if (!$facilities) {
            return;
        }

        $updatedFacilities = [];

        foreach ($facilities as $data) {
            $facilityModel = new FacilityModel([
                'title' => $data['facilitySpecification']['name'],
                'recranetBookingId' => $data['facilitySpecification']['id'],
                'organizationId' => $organization->getId(),
            ]);

            $facilityModel->validate();

            $facility = $this->findOrCreateElement($data['id'], $organization, $facilityModel, Facility::class);

            Craft::$app->elements->saveElement($facility);
            $updatedFacilities[] = $facility->id;
        }

        $this->removeStaleEntities($organization, $updatedFacilities, Facility::class);
    }

    public function importLocalityCategories(Organization $organization): void
    {
        $localityCategories = RecranetBooking::getInstance()->recranetBookingClient->fetchLocalityCategories($organization);

        if (!$localityCategories) {
            return;
        }

        $updatedLocalityCategories = [];

        foreach ($localityCategories as $data) {
            $localityCategoryModel = new LocalityCategoryModel([
                'title' => $data['displayName'],
                'recranetBookingId' => $data['id'],
                'organizationId' => $organization->getId(),
            ]);

            $localityCategoryModel->validate();

            $localityCategory = $this->findOrCreateElement($data['id'], $organization, $localityCategoryModel, LocalityCategory::class);

            Craft::$app->elements->saveElement($localityCategory);
            $updatedLocalityCategories[] = $localityCategory->id;
        }

        $this->removeStaleEntities($organization, $updatedLocalityCategories, LocalityCategory::class);
    }

    public function importPackageSpecificationCategories(Organization $organization): void
    {
        $packageSpecificationCategories = RecranetBooking::getInstance()->recranetBookingClient->fetchPackageSpecificationCategories($organization);

        if (!$packageSpecificationCategories) {
            return;
        }

        $updatedPackageSpecificationCategories = [];

        foreach ($packageSpecificationCategories as $data) {
            $packageSpecificationCategoryModel = new PackageSpecificationCategoryModel([
                'title' => $data['description'],
                'recranetBookingId' => $data['id'],
                'organizationId' => $organization->getId(),
            ]);

            $packageSpecificationCategoryModel->validate();

            $packageSpecificationCategory = $this->findOrCreateElement($data['id'], $organization, $packageSpecificationCategoryModel, PackageSpecificationCategory::class);

            Craft::$app->elements->saveElement($packageSpecificationCategory);
            $updatedPackageSpecificationCategories[] = $packageSpecificationCategory->id;
        }

        $this->removeStaleEntities($organization, $updatedPackageSpecificationCategories, PackageSpecificationCategory::class);
    }

    private function importTranslatedAccommodationSlugs(Organization $organization): void
    {
        $locales = ['de', 'en', 'fr'];
        $translatedAccommodations = [];

        foreach ($locales as $locale) {
            $accommodations = RecranetBooking::getInstance()->recranetBookingClient->fetchAccommodations($locale, $organization);

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
                    ->organizationId($organization->getId())
                    ->one()
                ;

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

    private function removeStaleEntities(Organization $organization, array $excludeEntities, string $className): void
    {
        // @TODO: THOUROUGHLY TEST
        $allEntities = $className::find()
            ->organizationId($organization->getId())
            ->all()
        ;

        foreach ($allEntities as $entity) {
            if (!in_array($entity->id, $excludeEntities)) {
                Craft::$app->elements->deleteElement($entity);
            }
        }
    }

    private function findOrCreateElement($id, Organization $organization, Model $model, string $className): Element
    {
        $element = $className::find()
            ->recranetBookingId($id)
            ->organizationId($organization->getId())
            ->one();

        if (!$element) {
            $element = new $className();
            $element->recranetBookingId = $model->recranetBookingId;
            $element->organizationId = $model->organizationId;
        }

        $element->title = $model->title;

        return $element;
    }
}
