<?php

namespace recranet\craftrecranetbooking\models;

use Craft;
use craft\base\Model;
use craft\elements\Entry;
use craft\models\Site;

/**
 * Organization model
 */
class Organization extends Model
{
    public int $recranetBookingId;
    public ?int $bookPageEntry = null;
    public string $bookPageEntryTemplate = '';
    public ?Site $site = null;

    public function getBookPageEntry(): ?Entry
    {
        if (!$this->bookPageEntry) {
            return null;
        }

        $currentSiteId = Craft::$app->getSites()->getCurrentSite()->getId();

        return Craft::$app->entries->getEntryById($this->bookPageEntry, $currentSiteId);
    }

    public function getBookPageEntryTemplate(): string
    {
        return $this->bookPageEntryTemplate ?: '';
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['recranetBookingId', 'title', 'bookPageEntry', 'bookPageEntryTemplate'], 'required'],
            [['recranetBookingId', 'bookPageEntry'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ]);
    }
}
