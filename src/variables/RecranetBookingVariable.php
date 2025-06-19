<?php

namespace recranet\craftrecranetbooking\variables;

use craft\helpers\App;
use recranet\craftrecranetbooking\RecranetBooking;

class RecranetBookingVariable
{
    public function getOrganizationId() : string
    {

        return App::parseEnv(RecranetBooking::getInstance()->getSettings()->organizationId);
    }
}