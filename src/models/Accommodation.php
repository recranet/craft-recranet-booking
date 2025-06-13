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
    public string $slug;
    public ?string $slugDe = '';
    public ?string $slugEn = '';
    public ?string $slugFr = '';
    public int $recranetBookingId;

    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['recranetBookingId', 'title', 'slug'], 'required'],
            [['recranetBookingId'], 'integer'],
            [['slug', 'slugDe', 'slugEn', 'slugFr'], 'match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            [['slug', 'slugDe', 'slugEn', 'slugFr'], 'string', 'max' => 255],
            ['title', 'string', 'max' => 255],
        ]);
    }
}
