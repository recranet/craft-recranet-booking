<?php

namespace recranet\craftrecranetbooking\fields;

use Craft;
use craft\base\ElementInterface;
use craft\fields\Dropdown;
use recranet\craftrecranetbooking\elements\Organization;
use yii\db\Schema;

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

        $options = [
            [
                'label' => '',
                'value' => null,
                'default' => false,
            ]
        ];
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
    public function serializeValue(mixed $value, ?ElementInterface $element = null): mixed
    {
        // Let parent extract the value from SingleOptionFieldData, then convert to int
        $serialized = parent::serializeValue($value, $element);

        // Convert to integer for database storage
        return $serialized !== null && $serialized !== '' ? (int)$serialized : null;
    }
}
