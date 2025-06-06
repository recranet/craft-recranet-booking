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
     * _recranet-booking/accommodations command
     */
    public function actionIndex(): int
    {
        RecranetBooking::getInstance()->import->importAccommodations();

        return ExitCode::OK;
    }
}
