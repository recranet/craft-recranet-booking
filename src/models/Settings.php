<?php

namespace recranet\craftrecranetbooking\models;

use Craft;
use craft\base\Model;
use craft\elements\Entry;

/**
 * Recranet Booking settings
 */
class Settings extends Model
{
    public string $organizationId = '';
    public int|null $bookPageEntry = null;
    public bool $sitemapEnabled = true;

    public function getBookPageEntry(): ?Entry
    {
        return $this->bookPageEntry ? Craft::$app->entries->getEntryById($this->bookPageEntry) : null;
    }

    public function rules(): array
    {
        return [
            [['bookPageEntry', 'organizationId'], 'required'],
            [['bookPageEntry'], 'integer'],
            [['sitemapEnabled'], 'boolean'],
            [['sitemapEnabled'], 'default', 'value' => true],
        ];
    }
}
