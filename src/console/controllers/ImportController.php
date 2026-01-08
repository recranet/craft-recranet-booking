<?php

namespace recranet\craftrecranetbooking\console\controllers;

use craft\console\Controller;
use recranet\craftrecranetbooking\RecranetBooking;

/**
 * Import controller
 */
class ImportController extends Controller
{
    public const EXIT_CODE_OK = 0;
    public const EXIT_CODE_UNSPECIFIED_ERROR = 1;

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
        foreach (RecranetBooking::getInstance()->getOrganizationService()->getLinkedOrganizations() as $organization) {
            if (!$organization || !$organization->recranetBookingId) {
                $this->stderr('No valid Organization ID provided.', self::EXIT_CODE_UNSPECIFIED_ERROR);

                return self::EXIT_CODE_UNSPECIFIED_ERROR;
            }

            $this->stdout("Importing all accommodations...\n");

            RecranetBooking::getInstance()->import->importAccommodations($organization);
        }

        return self::EXIT_CODE_OK;
    }

    /**
     * Imports accommodation categories
     * Usage: ./craft import/accommodation-categories
     */
    public function actionAccommodationCategories(): int
    {
        foreach (RecranetBooking::getInstance()->getOrganizationService()->getLinkedOrganizations() as $organization) {
            if (!$organization || !$organization->recranetBookingId) {
                $this->stderr('No valid Organization ID provided.', self::EXIT_CODE_UNSPECIFIED_ERROR);

                return self::EXIT_CODE_UNSPECIFIED_ERROR;
            }

            $this->stdout("Importing all accommodation categories...\n");

            RecranetBooking::getInstance()->import->importAccommodationCategories($organization);
        }

        return self::EXIT_CODE_OK;
    }

    /**
     * Imports locality categories
     * Usage: ./craft import/locality-categories
     */
    public function actionLocalityCategories(): int
    {
        foreach (RecranetBooking::getInstance()->getOrganizationService()->getLinkedOrganizations() as $organization) {
            if (!$organization || !$organization->recranetBookingId) {
                $this->stderr('No valid Organization ID provided.', self::EXIT_CODE_UNSPECIFIED_ERROR);

                return self::EXIT_CODE_UNSPECIFIED_ERROR;
            }

            $this->stdout("Importing all locality categories...\n");

            RecranetBooking::getInstance()->import->importLocalityCategories($organization);
        }

        return self::EXIT_CODE_OK;
    }

    /**
     * Imports package specification categories
     * Usage: ./craft import/package-specification-categories
     */
    public function actionPackageSpecificationCategories(): int
    {
        foreach (RecranetBooking::getInstance()->getOrganizationService()->getLinkedOrganizations() as $organization) {
            if (!$organization || !$organization->recranetBookingId) {
                $this->stderr('No valid Organization ID provided.', self::EXIT_CODE_UNSPECIFIED_ERROR);

                return self::EXIT_CODE_UNSPECIFIED_ERROR;
            }

            $this->stdout("Importing all package specification categories...\n");

            RecranetBooking::getInstance()->import->importPackageSpecificationCategories($organization);
        }

        return self::EXIT_CODE_OK;
    }

    /**
     * Imports facilities
     * Usage: ./craft import/facilities
     */
    public function actionFacilities(): int
    {
        foreach (RecranetBooking::getInstance()->getOrganizationService()->getLinkedOrganizations() as $organization) {
            if (!$organization || !$organization->recranetBookingId) {
                $this->stderr('No valid Organization ID provided.', self::EXIT_CODE_UNSPECIFIED_ERROR);

                return self::EXIT_CODE_UNSPECIFIED_ERROR;
            }

            $this->stdout("Importing all facilities...\n");

            RecranetBooking::getInstance()->import->importFacilities($organization);
        }

        return self::EXIT_CODE_OK;
    }
}
