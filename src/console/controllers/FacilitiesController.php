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
        RecranetBooking::getInstance()->import->importFacilities();

        return ExitCode::OK;
    }
}
