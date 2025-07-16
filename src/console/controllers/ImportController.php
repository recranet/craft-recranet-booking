<?php

namespace recranet\craftrecranetbooking\console\controllers;

use Craft;
use craft\console\Controller;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\console\ExitCode;

/**
 * Import controller
 */
class ImportController extends Controller
{
    public function actionIndex(): int
    {
        $this->actionAccommodations();
        $this->actionAccommodationCategories();
        $this->actionFacilities();
        $this->actionLocalityCategories();
        $this->actionPackageSpecificationCategories();

        return ExitCode::OK;
    }

    /**
     * Imports accommodations
     * Usage: ./craft import/accommodations
     */
    public function actionAccommodations(): int
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            $this->stderr('Organization ID is not set in the plugin settings.', ExitCode::UNSPECIFIED_ERROR);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("Importing all accommodations...\n");


        RecranetBooking::getInstance()->import->importAccommodations();

        return ExitCode::OK;
    }

    /**
     * Imports accommodation categories
     * Usage: ./craft import/accommodation-categories
     */
    public function actionAccommodationCategories(): int
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            $this->stderr('Organization ID is not set in the plugin settings.', ExitCode::UNSPECIFIED_ERROR);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("Importing all accommodation categories...\n");

        RecranetBooking::getInstance()->import->importAccommodationCategories();

        return ExitCode::OK;
    }

    /**
     * Imports locality categories
     * Usage: ./craft import/locality-categories
     */
    public function actionLocalityCategories(): int
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            $this->stderr('Organization ID is not set in the plugin settings.', ExitCode::UNSPECIFIED_ERROR);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("Importing all locality categories...\n");

        RecranetBooking::getInstance()->import->importLocalityCategories();

        return ExitCode::OK;
    }

    /**
     * Imports package specification categories
     * Usage: ./craft import/package-specification-categories
     */
    public function actionPackageSpecificationCategories(): int
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            $this->stderr('Organization ID is not set in the plugin settings.', ExitCode::UNSPECIFIED_ERROR);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("Importing all package specification categories...\n");

        RecranetBooking::getInstance()->import->importPackageSpecificationCategories();

        return ExitCode::OK;
    }

    /**
     * Imports facilities
     * Usage: ./craft import/facilities
     */
    public function actionFacilities(): int
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            $this->stderr('Organization ID is not set in the plugin settings.', ExitCode::UNSPECIFIED_ERROR);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("Importing all facilities...\n");

        RecranetBooking::getInstance()->import->importFacilities();

        return ExitCode::OK;
    }
}
