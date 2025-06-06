<?php

namespace recranet\craftrecranetbooking\console\controllers;

use Craft;
use craft\console\Controller;
use recranet\craftrecranetbooking\models\Facility;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\console\ExitCode;
use recranet\craftrecranetbooking\elements\Facility as FacilityElement;

/**
 * Facilities controller
 */
class FacilitiesController extends Controller
{
    public $defaultAction = 'index';

    public function options($actionID): array
    {
        $options = parent::options($actionID);
        switch ($actionID) {
            case 'index':
                // $options[] = '...';
                break;
        }
        return $options;
    }

    /**
     * _recranet-booking/facilities command
     */
    public function actionIndex(): int
    {
        RecranetBooking::getInstance()->import->importFacilities();
//        $facility = new Facility();
//        $facility->title = 'Test Facility';
//        $facility->recranetBookingId = 12345;
//
//        $facility->validate();
//
//        $facilityElement = new FacilityElement();
//        $facilityElement->title = $facility->title;
//        $facilityElement->recranetBookingId = $facility->recranetBookingId;
//
//        dump(Craft::$app->getElements()->saveElement($facilityElement));


        return ExitCode::OK;
    }
}
