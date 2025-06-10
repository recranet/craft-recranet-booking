<?php

namespace recranet\craftrecranetbooking\fields;

use Craft;
use craft\elements\ElementCollection;
use craft\fields\BaseRelationField;
use recranet\craftrecranetbooking\elements\db\LocalityCategoryQuery;
use recranet\craftrecranetbooking\elements\LocalityCategory;

/**
 * Locality Category Select field type
 */
class LocalityCategorySelect extends BaseRelationField
{
    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Localities');
    }

    public static function icon(): string
    {
        return '@recranet/craftrecranetbooking/icon-dashboard.svg';
    }

    public static function phpType(): string
    {
        return sprintf('\\%s|\\%s<\\%s>', LocalityCategoryQuery::class, ElementCollection::class, LocalityCategory::class);
    }

    public static function elementType(): string
    {
        return LocalityCategory::class;
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('_recranet-booking', 'Add locality');
    }
}
