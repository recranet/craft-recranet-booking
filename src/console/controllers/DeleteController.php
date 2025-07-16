<?php

namespace recranet\craftrecranetbooking\console\controllers;

use craft\console\Controller;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\console\ExitCode;

/**
 * Delete controller
 */
class DeleteController extends Controller
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
     * Deletes all accommodations
     * Usage: ./craft import/accommodations/delete
     */
    public function actionAccommodations(): int
    {
        $this->stdout("Deleting all accommodations...\n");

        RecranetBooking::getInstance()->accommodationService->deleteAll();

        return ExitCode::OK;
    }

    /**
     * Deletes all accommodation categories
     * Usage: ./craft import/accommodation-categories/delete
     */
    public function actionAccommodationCategories(): int
    {
        $this->stdout("Deleting all accommodation categories...\n");

        RecranetBooking::getInstance()->accommodationCategoryService->deleteAll();

        return ExitCode::OK;
    }

    /**
     * Deletes all locality categories
     * Usage: ./craft import/locality-categories/delete
     */
    public function actionLocalityCategories(): int
    {
        $this->stdout("Deleting all locality categories...\n");

        RecranetBooking::getInstance()->localityCategoryService->deleteAll();

        return ExitCode::OK;
    }

    /**
     * Deletes all package specification categories
     * Usage: ./craft import/package-specification-categories/delete
     */
    public function actionPackageSpecificationCategories(): int
    {
        $this->stdout("Deleting all package specification categories...\n");

        RecranetBooking::getInstance()->packageSpecificationCategoryService->deleteAll();

        return ExitCode::OK;
    }

    /**
     * Deletes all facilities
     * Usage: ./craft import/facilities/delete
     */
    public function actionFacilities(): int
    {
        $this->stdout("Deleting all facilities...\n");

        RecranetBooking::getInstance()->facilityService->deleteAll();

        return ExitCode::OK;
    }
}
