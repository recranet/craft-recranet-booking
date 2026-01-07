<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\helpers\App;
use recranet\craftrecranetbooking\elements\Organization as OrganizationElement;
use recranet\craftrecranetbooking\RecranetBooking;
use Throwable;
use yii\base\Component;

/**
 * Recranet Booking Client service
 */
class RecranetBookingClient extends Component
{
    public function fetchAccommodations(string $locale, OrganizationElement $organization): ?array
    {
        if (!$organization->recranetBookingId) {
            return [];
        }

        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/accommodations', [
                'query' => [
                    'organization' => $organization->recranetBookingId,
                    'locale' => $locale
                ],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function fetchAccommodationCategories(OrganizationElement $organization): ?array
    {
        if (!$organization->recranetBookingId) {
            return [];
        }

        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/accommodation_categories', [
                'query' => ['organization' => $organization->recranetBookingId],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function fetchFacilities(OrganizationElement $organization): ?array
    {
        if (!$organization->recranetBookingId) {
            return [];
        }

        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/facilities', [
                'query' => ['organization' => $organization->recranetBookingId],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function fetchLocalityCategories(OrganizationElement $organization): ?array
    {
        if (!$organization->recranetBookingId) {
            return [];
        }

        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/locality_categories', [
                'query' => ['organization' => $organization->recranetBookingId],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function fetchPackageSpecificationCategories(OrganizationElement $organization): ?array
    {
        if (!$organization->recranetBookingId) {
            return [];
        }

        try {
            $response = Craft::createGuzzleClient()->get('https://app.recranet.com/api/package_specification_categories', [
                'query' => ['organization' => $organization->recranetBookingId],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
