<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use recranet\craftrecranetbooking\RecranetBooking;
use Throwable;
use yii\base\Component;

/**
 * Recranet Booking Client service
 */
class RecranetBookingClient extends Component
{
    public function fetchFacilities(): ?array
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            return [];
        }

        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/facilities', [
                'query' => ['organization' => $organizationId],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function fetchAccommodations(): ?array
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            return [];
        }

        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/accommodations', [
                'query' => ['organization' => $organizationId],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
