<?php

namespace recranet\craftrecranetbooking\console\controllers;

use Craft;
use craft\console\Controller;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\console\ExitCode;

/**
 * Accommodation Category controller
 */
class AccommodationCategoryController extends Controller
{
    public $defaultAction = 'index';

    /**
     * This command imports accommodation categories from the Recranet Booking API based off organization ID.
     * _recranet-booking/accommodation-category
     */
    public function actionIndex(): int
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            $this->stderr('Organization ID is not set in the plugin settings.', ExitCode::UNSPECIFIED_ERROR);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        RecranetBooking::getInstance()->import->importAccommodationCategories();

        return ExitCode::OK;
    }
}
