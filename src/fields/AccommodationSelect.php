<?php

namespace recranet\craftrecranetbooking\fields;

use Craft;
use craft\elements\ElementCollection;
use craft\fields\BaseRelationField;
use recranet\craftrecranetbooking\elements\Accommodation;
use recranet\craftrecranetbooking\elements\db\AccommodationQuery;

/**
 * Accommodation Select field type
 */
class AccommodationSelect extends BaseRelationField
{
    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Accommodations');
    }

    public static function icon(): string
    {
        return '@recranet/craftrecranetbooking/icon-dashboard.svg';
    }

    public static function phpType(): string
    {
        return sprintf('\\%s|\\%s<\\%s>', AccommodationQuery::class, ElementCollection::class, Accommodation::class);
    }

    public static function elementType(): string
    {
        return Accommodation::class;
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('_recranet-booking', 'Add accommodations');
    }
}
