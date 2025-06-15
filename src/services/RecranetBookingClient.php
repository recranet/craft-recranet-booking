<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\helpers\App;
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

    public function fetchAccommodationCategories(): ?array
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            return [];
        }

        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/accommodation_categories', [
                'query' => ['organization' => $organizationId],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function fetchLocalityCategories(): ?array
    {
        $organizationId = RecranetBooking::getInstance()->getSettings()->organizationId;

        if (!$organizationId) {
            return [];
        }

        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/locality_categories', [
                'query' => ['organization' => $organizationId],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function fetchAccommodations(string $locale): ?array
    {
        $organizationId = App::parseEnv(RecranetBooking::getInstance()->getSettings()->organizationId);

        if (!$organizationId) {
            return [];
        }

        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/accommodations', [
                'query' => [
                    'organization' => $organizationId,
                    'locale' => $locale
                ],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
