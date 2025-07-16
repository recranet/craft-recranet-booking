<?php

namespace recranet\craftrecranetbooking\models;

use Craft;
use craft\base\Model;

/**
 * Accommodation model
 */
class Accommodation extends Model
{
    public string $title;
    public string|null $titleDe = '';
    public string|null $titleEn = '';
    public string|null $titleFr = '';
    public string $slug;
    public string|null $slugDe = '';
    public string|null $slugEn = '';
    public string|null $slugFr = '';
    public int $recranetBookingId;

    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['recranetBookingId', 'title', 'slug'], 'required'],
            [['recranetBookingId'], 'integer'],
            [['slug', 'slugDe', 'slugEn', 'slugFr', 'titleDe', 'titleEn', 'titleFr'], 'match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            ['title', 'string', 'max' => 255],
        ]);
    }
}
