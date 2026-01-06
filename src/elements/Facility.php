<?php

namespace recranet\craftrecranetbooking\elements;

use Craft;
use craft\base\Element;
use craft\elements\User;
use craft\elements\conditions\ElementConditionInterface;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\UrlHelper;
use craft\web\CpScreenResponseBehavior;
use recranet\craftrecranetbooking\elements\conditions\FacilityCondition;
use recranet\craftrecranetbooking\elements\db\FacilityQuery;
use yii\web\Response;
use recranet\craftrecranetbooking\records\Facility as FacilityRecord;

/**
 * Facility element type
 */
class Facility extends Element
{
    public int $recranetBookingId = 0;
    public ?int $organizationId = null;

    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Facility');
    }

    public static function lowerDisplayName(): string
    {
        return Craft::t('_recranet-booking', 'facility');
    }

    public static function pluralDisplayName(): string
    {
        return Craft::t('_recranet-booking', 'Facilities');
    }

    public static function pluralLowerDisplayName(): string
    {
        return Craft::t('_recranet-booking', 'facilities');
    }

    public static function refHandle(): ?string
    {
        return 'facility';
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
        return new FacilityQuery(static::class);
    }

    public static function createCondition(): ElementConditionInterface
    {
        return Craft::createObject(FacilityCondition::class, [static::class]);
    }

    protected static function defineSources(string $context): array
    {
        return [
            [
                'key' => '*',
                'label' => Craft::t('_recranet-booking', 'All facilities'),
            ],
        ];
    }

    protected static function defineActions(string $source): array
    {
        // List any bulk element actions here
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
            'uri' => Craft::t('app', 'URI'),
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
            // ...
        ];
    }

    protected static function defineTableAttributes(): array
    {
        return [
            'recranetBookingId' => ['label' => Craft::t('app', 'Recranet Booking ID')],
            'organizationId' => ['label' => Craft::t('app', 'Organization')],
            'slug' => ['label' => Craft::t('app', 'Slug')],
            'uri' => ['label' => Craft::t('app', 'URI')],
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
        // todo: implement user permissions
        return $user->can('viewFacilities');
    }

    public function canSave(User $user): bool
    {
        if (parent::canSave($user)) {
            return true;
        }
        // todo: implement user permissions
        return $user->can('saveFacilities');
    }

    public function canDuplicate(User $user): bool
    {
        if (parent::canDuplicate($user)) {
            return true;
        }
        // todo: implement user permissions
        return $user->can('saveFacilities');
    }

    public function canDelete(User $user): bool
    {
        if (parent::canSave($user)) {
            return true;
        }
        // todo: implement user permissions
        return $user->can('deleteFacilities');
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
        return UrlHelper::cpUrl('facilities');
    }

    public function prepareEditScreen(Response $response, string $containerId): void
    {
        /** @var Response|CpScreenResponseBehavior $response */
        $response->crumbs([
            [
                'label' => self::pluralDisplayName(),
                'url' => UrlHelper::cpUrl('facilities'),
            ],
        ]);
    }

    public function afterSave(bool $isNew): void
    {
        if (!$this->propagating) {
            $record = FacilityRecord::findOne($this->id);
            if (!$record) {
                $record = new FacilityRecord();
                $record->id = $this->id;
            }

            $record->title = $this->title;
            $record->recranetBookingId = $this->recranetBookingId;
            $record->organizationId = $this->organizationId;

            $record->save();
        }

        parent::afterSave($isNew);
    }

    public function afterDelete(): void
    {
        parent::afterDelete();

        Craft::$app->db->createCommand()
            ->delete('{{%_recranet-booking_facilities}}', ['recranetBookingId' => $this->recranetBookingId])
            ->execute();
    }
}
