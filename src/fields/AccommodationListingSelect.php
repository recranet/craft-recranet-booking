<?php

namespace recranet\craftrecranetbooking\fields;

use Craft;
use craft\elements\ElementCollection;
use recranet\craftrecranetbooking\elements\AccommodationListing;
use recranet\craftrecranetbooking\elements\db\AccommodationListingQuery;

/**
 * Accommodation Listing Select field type
 */
class AccommodationListingSelect extends RecranetSelectWithOrganizationFilter
{
    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Accommodation listing');
    }

    public static function icon(): string
    {
        return '@recranet/craftrecranetbooking/icon-dashboard.svg';
    }

    public static function phpType(): string
    {
        return sprintf('\\%s|\\%s<\\%s>', AccommodationListingQuery::class, ElementCollection::class, AccommodationListing::class);
    }

    public static function elementType(): string
    {
        return AccommodationListing::class;
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('_recranet-booking', 'Add a listing');
    }
}
