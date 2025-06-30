<?php

namespace recranet\craftrecranetbooking\console\controllers;

use craft\console\Controller;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\console\ExitCode;

/**
 * Facilities controller
 */
class FacilitiesController extends Controller
{
    public $defaultAction = 'index';

    /**
     * This command imports facilities from the Recranet Booking API based off organization ID.
     * _recranet-booking/facilities
     */
    public function actionIndex(): int
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            $this->stderr('Organization ID is not set in the plugin settings.', ExitCode::UNSPECIFIED_ERROR);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        RecranetBooking::getInstance()->import->importFacilities();

        return ExitCode::OK;
    }

    public function actionDeleteAll(): int
    {
        $this->stdout("Deleting all facilities...\n");

        RecranetBooking::getInstance()->facilityService->deleteAll();

        return ExitCode::OK;
    }
}
