<?php

namespace recranet\craftrecranetbooking\fields;

use Craft;
use craft\elements\ElementCollection;
use recranet\craftrecranetbooking\elements\db\FacilityQuery;
use recranet\craftrecranetbooking\elements\Facility;

/**
 * Facility Select field type
 */
class FacilitySelect extends RecranetSelectWithOrganizationFilter
{
    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Facilities');
    }

    public static function icon(): string
    {
        return '@recranet/craftrecranetbooking/icon-dashboard.svg';
    }

    public static function phpType(): string
    {
        return sprintf('\\%s|\\%s<\\%s>', FacilityQuery::class, ElementCollection::class, Facility::class);
    }

    public static function elementType(): string
    {
        return Facility::class;
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('_recranet-booking', 'Add facilities');
    }
}
