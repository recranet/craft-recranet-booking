<?php

namespace recranet\craftrecranetbooking\elements\db;

use craft\elements\db\ElementQuery;
use recranet\craftrecranetbooking\elements\Accommodation;

/**
 * Accommodation query
 */
class AccommodationQuery extends ElementQuery
{
    public int $recranetBookingId = 0;
    public ?string $slugDe = '';
    public ?string $slugEn = '';
    public ?string $slugFr = '';
    public ?string $titleDe = '';
    public ?string $titleEn = '';
    public ?string $titleFr = '';

    public function __construct(string $elementType = null, array $config = [])
    {
        if ($elementType === null) {
            $elementType = Accommodation::class;
        }

        parent::__construct($elementType, $config);
    }

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

        if ($this->titleDe) {
            $this->subQuery->andWhere(['titleDe' => $this->titleDe]);
        }

        if ($this->titleEn) {
            $this->subQuery->andWhere(['titleEn' => $this->titleEn]);
        }

        if ($this->titleFr) {
            $this->subQuery->andWhere(['titleFr' => $this->titleFr]);
        }

        $this->query->select([
            '_recranet-booking_accommodations.title',
            '_recranet-booking_accommodations.slug',
            '_recranet-booking_accommodations.slugDe',
            '_recranet-booking_accommodations.slugEn',
            '_recranet-booking_accommodations.slugFr',
            '_recranet-booking_accommodations.titleDe',
            '_recranet-booking_accommodations.titleEn',
            '_recranet-booking_accommodations.titleFr',
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

    public function titleDe($value): self
    {
        $this->titleDe = $value;
        return $this;
    }

    public function titleEn($value): self
    {
        $this->titleEn = $value;
        return $this;
    }

    public function titleFr($value): self
    {
        $this->titleFr = $value;
        return $this;
    }
}
