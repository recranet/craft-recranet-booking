<?php

namespace recranet\craftrecranetbooking\elements;

use Craft;
use craft\base\Element;
use craft\elements\conditions\ElementConditionInterface;
use craft\elements\db\ElementQueryInterface;
use craft\elements\User;
use craft\helpers\UrlHelper;
use craft\web\CpScreenResponseBehavior;
use recranet\craftrecranetbooking\elements\conditions\AccommodationListingCondition;
use recranet\craftrecranetbooking\elements\db\AccommodationListingQuery;
use recranet\craftrecranetbooking\records\AccommodationListing as AccommodationListingRecord;
use yii\web\Response;

/**
 * Accommodation Listing element type
 */
class AccommodationListing extends Element
{
    public int $recranetBookingId = 0;
    public ?int $organizationId = null;
    public string $locale = '';

    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Accommodation listing');
    }

    public static function lowerDisplayName(): string
    {
        return Craft::t('_recranet-booking', 'accommodation listing');
    }

    public static function pluralDisplayName(): string
    {
        return Craft::t('_recranet-booking', 'Accommodation listings');
    }

    public static function pluralLowerDisplayName(): string
    {
        return Craft::t('_recranet-booking', 'accommodation listings');
    }

    public static function refHandle(): ?string
    {
        return 'accommodationlisting';
    }

    public static function trackChanges(): bool
    {
        return true;
    }

    public static function hasTitles(): bool
    {
        return true;
    }

    public static function hasUris(): bool
    {
        return false;
    }

    public static function isLocalized(): bool
    {
        return false;
    }

    public static function hasStatuses(): bool
    {
        return true;
    }

    public static function find(): ElementQueryInterface
    {
        return new AccommodationListingQuery(static::class);
    }

    public static function createCondition(): ElementConditionInterface
    {
        return Craft::createObject(AccommodationListingCondition::class, [static::class]);
    }

    protected static function defineSources(string $context): array
    {
        return [
            [
                'key' => '*',
                'label' => Craft::t('_recranet-booking', 'All listings'),
            ],
        ];
    }

    protected static function defineActions(string $source): array
    {
        return [];
    }

    protected static function includeSetStatusAction(): bool
    {
        return true;
    }

    protected static function defineSortOptions(): array
    {
        return [
            'title' => Craft::t('app', 'Title'),
            'slug' => Craft::t('app', 'Slug'),
            [
                'label' => Craft::t('app', 'Date Created'),
                'orderBy' => 'elements.dateCreated',
                'attribute' => 'dateCreated',
                'defaultDir' => 'desc',
            ],
            [
                'label' => Craft::t('app', 'Date Updated'),
                'orderBy' => 'elements.dateUpdated',
                'attribute' => 'dateUpdated',
                'defaultDir' => 'desc',
            ],
            [
                'label' => Craft::t('app', 'ID'),
                'orderBy' => 'elements.id',
                'attribute' => 'id',
            ],
        ];
    }

    protected static function defineTableAttributes(): array
    {
        return [
            'recranetBookingId' => ['label' => Craft::t('app', 'Recranet Booking ID')],
            'organizationId' => ['label' => Craft::t('app', 'Organization')],
            'locale' => ['label' => Craft::t('app', 'Locale')],
            'slug' => ['label' => Craft::t('app', 'Slug')],
            'id' => ['label' => Craft::t('app', 'ID')],
            'uid' => ['label' => Craft::t('app', 'UID')],
            'dateCreated' => ['label' => Craft::t('app', 'Date Created')],
            'dateUpdated' => ['label' => Craft::t('app', 'Date Updated')],
        ];
    }

    protected static function defineDefaultTableAttributes(string $source): array
    {
        return [
            'dateCreated',
            'dateUpdated',
            'recranetBookingId',
            'organizationId',
            'locale',
        ];
    }

    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            // ...
        ]);
    }

    public function getUriFormat(): ?string
    {
        return null;
    }

    protected function previewTargets(): array
    {
        $previewTargets = [];
        $url = $this->getUrl();
        if ($url) {
            $previewTargets[] = [
                'label' => Craft::t('app', 'Primary {type} page', [
                    'type' => self::lowerDisplayName(),
                ]),
                'url' => $url,
            ];
        }
        return $previewTargets;
    }

    protected function route(): array|string|null
    {
        return null;
    }

    public function canView(User $user): bool
    {
        if (parent::canView($user)) {
            return true;
        }
        return $user->can('viewAccommodationListings');
    }

    public function canSave(User $user): bool
    {
        if (parent::canSave($user)) {
            return true;
        }
        return $user->can('saveAccommodationListings');
    }

    public function canDuplicate(User $user): bool
    {
        if (parent::canDuplicate($user)) {
            return true;
        }
        return $user->can('saveAccommodationListings');
    }

    public function canDelete(User $user): bool
    {
        if (parent::canSave($user)) {
            return true;
        }
        return $user->can('deleteAccommodationListings');
    }

    public function canCreateDrafts(User $user): bool
    {
        return true;
    }

    protected function cpEditUrl(): ?string
    {
        return null;
    }

    public function getPostEditUrl(): ?string
    {
        return UrlHelper::cpUrl('recranet-booking/accommodation-listings');
    }

    public function prepareEditScreen(Response $response, string $containerId): void
    {
        /** @var Response|CpScreenResponseBehavior $response */
        $response->crumbs([
            [
                'label' => self::pluralDisplayName(),
                'url' => UrlHelper::cpUrl('recranet-booking/accommodation-listings'),
            ],
        ]);
    }

    /**
     * Returns the Accommodation elements associated with this listing via the pivot table.
     *
     * @return Accommodation[]
     */
    public function getAccommodations(): array
    {
        $accommodationIds = (new \yii\db\Query())
            ->select(['accommodationId'])
            ->from('{{%_recranet_booking_accommodation_listing_accommodations}}')
            ->where(['listingId' => $this->id])
            ->column();

        if (empty($accommodationIds)) {
            return [];
        }

        return Accommodation::find()
            ->id($accommodationIds)
            ->all();
    }

    public function afterSave(bool $isNew): void
    {
        if (!$this->propagating) {
            $record = AccommodationListingRecord::findOne($this->id);
            if (!$record) {
                $record = new AccommodationListingRecord();
                $record->id = $this->id;
            }

            $record->title = $this->title;
            $record->recranetBookingId = $this->recranetBookingId;
            $record->organizationId = $this->organizationId;
            $record->locale = $this->locale;

            $record->save();
        }

        parent::afterSave($isNew);
    }

    public function afterDelete(): void
    {
        parent::afterDelete();

        Craft::$app->db->createCommand()
            ->delete('{{%_recranet_booking_accommodation_listing_accommodations}}', ['listingId' => $this->id])
            ->execute();

        Craft::$app->db->createCommand()
            ->delete('{{%_recranet_booking_accommodation_listings}}', ['id' => $this->id])
            ->execute();
    }
}
