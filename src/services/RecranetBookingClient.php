<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use Throwable;
use yii\base\Component;

/**
 * Recranet Booking Client service
 */
class RecranetBookingClient extends Component
{
    public function fetchFacilities(): ?array
    {
        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/facilities', [
                'query' => ['organization' => 1079],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
