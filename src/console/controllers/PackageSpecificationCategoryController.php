<?php

namespace recranet\craftrecranetbooking\console\controllers;

use craft\console\Controller;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\console\ExitCode;

/**
 * Package Specification Category controller
 */
class PackageSpecificationCategoryController extends Controller
{
    public $defaultAction = 'index';

    /**
     * This command imports package specification categories from the Recranet Booking API based off organization ID.
     * _recranet-booking/package-specification-category
     */
    public function actionIndex(): int
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            $this->stderr('Organization ID is not set in the plugin settings.', ExitCode::UNSPECIFIED_ERROR);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        RecranetBooking::getInstance()->import->importPackageSpecificationCategories();

        return ExitCode::OK;
    }

    public function actionDeleteAll(): int
    {
        $this->stdout("Deleting all package specification categories...\n");

        RecranetBooking::getInstance()->packageSpecificationCategoryService->deleteAll();

        return ExitCode::OK;
    }
}
