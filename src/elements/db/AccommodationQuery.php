<?php

namespace recranet\craftrecranetbooking\elements\db;

use Craft;
use craft\elements\db\ElementQuery;

/**
 * Accommodation query
 */
class AccommodationQuery extends ElementQuery
{
    public int $recranetBookingId = 0;
    public ?string $slugDe = '';
    public ?string $slugEn = '';
    public ?string $slugFr = '';

    protected function beforePrepare(): bool
    {
        $this->joinElementTable('_recranet-booking_accommodations');

        if ($this->recranetBookingId) {
            $this->subQuery->andWhere(['recranetBookingId' => $this->recranetBookingId]);
        }

        if ($this->slugDe) {
            $this->subQuery->andWhere(['slugDe' => $this->slugDe]);
        }

        if ($this->slugEn) {
            $this->subQuery->andWhere(['slugEn' => $this->slugEn]);
        }

        if ($this->slugFr) {
            $this->subQuery->andWhere(['slugFr' => $this->slugFr]);
        }

        $this->query->select([
            '_recranet-booking_accommodations.title',
            '_recranet-booking_accommodations.slug',
            '_recranet-booking_accommodations.slugDe',
            '_recranet-booking_accommodations.slugEn',
            '_recranet-booking_accommodations.slugFr',
            '_recranet-booking_accommodations.dateCreated',
            '_recranet-booking_accommodations.dateUpdated',
            '_recranet-booking_accommodations.recranetBookingId',
        ]);

        return parent::beforePrepare();
    }

    public function recranetBookingId($value): self
    {
        $this->recranetBookingId = $value;
        return $this;
    }

    public function slugDe($value): self
    {
        $this->slugDe = $value;
        return $this;
    }

    public function slugEn($value): self
    {
        $this->slugEn = $value;
        return $this;
    }

    public function slugFr($value): self
    {
        $this->slugFr = $value;
        return $this;
    }
}
