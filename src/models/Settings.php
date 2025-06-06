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
    public int $organizationId = 0;
    public int $bookPageEntry = 0;
    public bool $sitemapEnabled = true;

    public function getBookPageEntry(): ?Entry
    {
        return $this->bookPageEntry ? Craft::$app->entries->getEntryById($this->bookPageEntry) : null;
    }
}
