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
    public string|null $organizationId = null;
    public int|null $bookPageEntry = null;
    public string|null $bookPageEntryTemplate = null;
    public bool $sitemapEnabled = true;

    public function getBookPageEntry(): ?Entry
    {
        return $this->bookPageEntry ? Craft::$app->entries->getEntryById($this->bookPageEntry) : null;
    }

    public function getBookPageEntryTemplate(): ?string
    {
        return $this->bookPageEntryTemplate;
    }

    public function rules(): array
    {
        return [
            [['bookPageEntry', 'organizationId'], 'integer'],
            [['sitemapEnabled'], 'boolean'],
            [['sitemapEnabled'], 'default', 'value' => true],
        ];
    }
}
