<?php

namespace recranet\craftrecranetbooking\variables;

use recranet\craftrecranetbooking\RecranetBooking;

class RecranetBookingVariable
{
    public function getOrganizationId() : string
    {
        return RecranetBooking::getInstance()->getSettings()->organizationId;
    }
}