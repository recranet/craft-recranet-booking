<?php

namespace recranet\craftrecranetbooking\fields;

use Craft;
use craft\elements\ElementCollection;
use craft\fields\BaseRelationField;
use recranet\craftrecranetbooking\elements\AccommodationCategory;
use recranet\craftrecranetbooking\elements\db\AccommodationCategoryQuery;

/**
 * Accommodation Category Select field type
 */
class AccommodationCategorySelect extends BaseRelationField
{
    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Types');
    }

    public static function icon(): string
    {
        return '@recranet/craftrecranetbooking/icon-dashboard.svg';
    }

    public static function phpType(): string
    {
        return sprintf('\\%s|\\%s<\\%s>', AccommodationCategoryQuery::class, ElementCollection::class, AccommodationCategory::class);
    }

    public static function elementType(): string
    {
        return AccommodationCategory::class;
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('_recranet-booking', 'Add types');
    }
}
