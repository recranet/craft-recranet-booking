<?php

namespace recranet\craftrecranetbooking\console\controllers;

use Craft;
use craft\console\Controller;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\console\ExitCode;

/**
 * Accommodations controller
 */
class AccommodationsController extends Controller
{
    public $defaultAction = 'index';

    /**
     * This command imports accommodations from the Recranet Booking API based off organization ID.
     * _recranet-booking/accommodations command
     */
    public function actionIndex(): int
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            $this->stderr('Organization ID is not set in the plugin settings.', ExitCode::UNSPECIFIED_ERROR);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        RecranetBooking::getInstance()->import->importAccommodations();

        return ExitCode::OK;
    }

    public function actionDeleteAll(): int
    {
        $this->stdout("Deleting all accommodations...\n");

        RecranetBooking::getInstance()->accommodationService->deleteAll();

        return ExitCode::OK;
    }
}
