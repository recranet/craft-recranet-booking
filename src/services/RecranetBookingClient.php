<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use recranet\craftrecranetbooking\elements\Organization as OrganizationElement;
use Throwable;
use yii\base\Component;
use GuzzleHttp\Client;

/**
 * Recranet Booking Client service
 */
class RecranetBookingClient extends Component
{
    private const BASE_URL = 'https://app.recranet.com/public/api/';

    private Client $client;

    public function __construct()
    {
        $this->client = Craft::createGuzzleClient([
            'headers' => [
                'User-Agent' => 'recranet/craft-recranet-booking',
            ],
        ]);
    }

    public function fetchAccommodations(string $locale, OrganizationElement $organization): ?array
    {
        if (!$organization->recranetBookingId) {
            return [];
        }

        try {
            $response = $this->client->get(self::BASE_URL . 'accommodations', [
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
            $response = $this->client->get(self::BASE_URL . 'accommodation_categories', [
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
            $response = $this->client->get(self::BASE_URL . 'facilities', [
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
            $response = $this->client->get(self::BASE_URL . 'locality_categories', [
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
            $response = $this->client->get(self::BASE_URL . 'package_specification_categories', [
                'query' => ['organization' => $organization->recranetBookingId],
            ]);
        } catch (Throwable $e) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
