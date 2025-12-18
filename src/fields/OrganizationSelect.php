<?php

namespace recranet\craftrecranetbooking\fields;

use Craft;
use craft\elements\ElementCollection;
use craft\fields\BaseRelationField;
use recranet\craftrecranetbooking\elements\Organization;
use recranet\craftrecranetbooking\elements\db\OrganizationQuery;

/**
 * Accommodation Select field type
 */
class OrganizationSelect extends BaseRelationField
{
    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Organizations');
    }

    public static function icon(): string
    {
        return '@recranet/craftrecranetbooking/icon-dashboard.svg';
    }

    public static function phpType(): string
    {
        return sprintf('\\%s|\\%s<\\%s>', OrganizationQuery::class, ElementCollection::class, Organization::class);
    }

    public static function elementType(): string
    {
        return Organization::class;
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('_recranet-booking', 'Add organizations');
    }
}
