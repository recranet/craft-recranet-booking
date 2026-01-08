<?php

namespace recranet\craftrecranetbooking\models;

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
    public int $organizationId;

    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['recranetBookingId', 'organizationId', 'title', 'slug'], 'required'],
            [['recranetBookingId', 'organizationId'], 'integer'],
            [['slug', 'slugDe', 'slugEn', 'slugFr', 'titleDe', 'titleEn', 'titleFr'], 'match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            ['title', 'string', 'max' => 255],
        ]);
    }
}
