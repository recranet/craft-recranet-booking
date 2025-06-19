<?php

namespace recranet\craftrecranetbooking\fields;

use Craft;
use craft\elements\ElementCollection;
use craft\fields\BaseRelationField;
use recranet\craftrecranetbooking\elements\PackageSpecificationCategory;
use recranet\craftrecranetbooking\elements\db\PackageSpecificationCategoryQuery;

/**
 * Package Specification Category Select field type
 */
class PackageSpecificationCategorySelect extends BaseRelationField
{
    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Package Specifications Categories');
    }

    public static function icon(): string
    {
        return '@recranet/craftrecranetbooking/icon-dashboard.svg';
    }

    public static function phpType(): string
    {
        return sprintf('\\%s|\\%s<\\%s>', PackageSpecificationCategoryQuery::class, ElementCollection::class, PackageSpecificationCategory::class);
    }

    public static function elementType(): string
    {
        return PackageSpecificationCategory::class;
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('_recranet-booking', 'Add package specification category');
    }

    public function getMaxRelations(): ?int
    {
        return 1;
    }
}
