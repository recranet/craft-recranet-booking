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
        RecranetBooking::getInstance()->import->importAccommodations();

        return ExitCode::OK;
    }
}
