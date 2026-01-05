<?php

namespace recranet\craftrecranetbooking\fields;

use Craft;
use craft\fields\Dropdown;
use craft\base\ElementInterface;
use craft\fields\data\SingleOptionFieldData;
use yii\db\Schema;
use recranet\craftrecranetbooking\elements\Organization;

/**
 * Organization Dropdown field type
 *
 * Stores organization IDs as integers while providing a user-friendly dropdown interface
 */
class OrganizationDropdown extends Dropdown
{
    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Organization (Dropdown)');
    }

    public static function icon(): string
    {
        return '@recranet/craftrecranetbooking/icon-dashboard.svg';
    }

    public static function phpType(): string
    {
        return 'int|null';
    }

    public static function dbType(): string
    {
        return Schema::TYPE_INTEGER;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        // Don't show the options configuration UI since we generate options dynamically
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function options(): array
    {
        // Dynamically generate options from Organization elements
        $organizations = Organization::find()->all();

        $options = [];
        foreach ($organizations as $organization) {
            $options[] = [
                'label' => $organization->title,
                'value' => (string)$organization->id,
                'default' => false,
            ];
        }

        return $options;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue(mixed $value, ?ElementInterface $element = null): mixed
    {
        // Ensure we always return int|null, never SingleOptionFieldData
        if ($value instanceof SingleOptionFieldData) {
            return $value->value ? (int)$value->value : null;
        }

        if ($value === '' || $value === null) {
            return null;
        }

        return (int)$value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue(mixed $value, ?ElementInterface $element = null): mixed
    {
        // Store as integer in database
        return $value ? (int)$value : null;
    }
}
