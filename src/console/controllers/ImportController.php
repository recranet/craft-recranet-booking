<?php

namespace recranet\craftrecranetbooking\console\controllers;

use craft\console\Controller;
use recranet\craftrecranetbooking\elements\Organization;
use recranet\craftrecranetbooking\RecranetBooking;

/**
 * Import controller
 */
class ImportController extends Controller
{
    public const EXIT_CODE_OK = 0;
    public const EXIT_CODE_UNSPECIFIED_ERROR = 1;

    public ?string $organizationId = null;

    public function options($actionID): array
    {
        $options = parent::options($actionID);
        $options[] = 'organizationId';

        return $options;
    }

    public function actionIndex(): int
    {
        $this->actionAccommodations();
        $this->actionAccommodationCategories();
        $this->actionFacilities();
        $this->actionLocalityCategories();
        $this->actionPackageSpecificationCategories();

        return self::EXIT_CODE_OK;
    }

    /**
     * Imports accommodations
     * Usage: ./craft import/accommodations
     */
    public function actionAccommodations(): int
    {
        $organization = $this->getOrganization();

        if (!$organization || $organization->recranetBookingId) {
            $this->stderr('No valid Organization ID provided.', self::EXIT_CODE_UNSPECIFIED_ERROR);

            return self::EXIT_CODE_UNSPECIFIED_ERROR;
        }

        $this->stdout("Importing all accommodations...\n");

        RecranetBooking::getInstance()->import->importAccommodations($organization);

        return self::EXIT_CODE_OK;
    }

    /**
     * Imports accommodation categories
     * Usage: ./craft import/accommodation-categories
     */
    public function actionAccommodationCategories(): int
    {
        $organization = $this->getOrganization();

        if (!$organization || $organization->recranetBookingId) {
            $this->stderr('No valid Organization ID provided.', self::EXIT_CODE_UNSPECIFIED_ERROR);

            return self::EXIT_CODE_UNSPECIFIED_ERROR;
        }

        $this->stdout("Importing all accommodation categories...\n");

        RecranetBooking::getInstance()->import->importAccommodationCategories($organization);

        return self::EXIT_CODE_OK;
    }

    /**
     * Imports locality categories
     * Usage: ./craft import/locality-categories
     */
    public function actionLocalityCategories(): int
    {
        $organization = $this->getOrganization();

        if (!$organization || $organization->recranetBookingId) {
            $this->stderr('No valid Organization ID provided.', self::EXIT_CODE_UNSPECIFIED_ERROR);

            return self::EXIT_CODE_UNSPECIFIED_ERROR;
        }

        $this->stdout("Importing all locality categories...\n");

        RecranetBooking::getInstance()->import->importLocalityCategories($organization);

        return self::EXIT_CODE_OK;
    }

    /**
     * Imports package specification categories
     * Usage: ./craft import/package-specification-categories
     */
    public function actionPackageSpecificationCategories(): int
    {
        $organization = $this->getOrganization();

        if (!$organization || $organization->recranetBookingId) {
            $this->stderr('No valid Organization ID provided.', self::EXIT_CODE_UNSPECIFIED_ERROR);

            return self::EXIT_CODE_UNSPECIFIED_ERROR;
        }

        $this->stdout("Importing all package specification categories...\n");

        RecranetBooking::getInstance()->import->importPackageSpecificationCategories($organization);

        return self::EXIT_CODE_OK;
    }

    /**
     * Imports facilities
     * Usage: ./craft import/facilities
     */
    public function actionFacilities(): int
    {
        $organization = $this->getOrganization();

        if (!$organization || $organization->recranetBookingId) {
            $this->stderr('No valid Organization ID provided.', self::EXIT_CODE_UNSPECIFIED_ERROR);

            return self::EXIT_CODE_UNSPECIFIED_ERROR;
        }

        $this->stdout("Importing all facilities...\n");

        RecranetBooking::getInstance()->import->importFacilities($organization);

        return self::EXIT_CODE_OK;
    }

    private function getOrganization(): ?Organization
    {
        if ($this->organizationId) {
            return Organization::findOne(['recranetBookingId' => $this->organizationId]);
        }

        return null;
    }
}
