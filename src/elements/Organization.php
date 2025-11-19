<?php

namespace recranet\craftrecranetbooking\elements;

use Craft;
use craft\base\Element;
use craft\elements\User;
use craft\elements\conditions\ElementConditionInterface;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\UrlHelper;
use craft\web\CpScreenResponseBehavior;
use recranet\craftrecranetbooking\elements\conditions\OrganizationCondition;
use recranet\craftrecranetbooking\elements\db\OrganizationQuery;
use yii\web\Response;
use recranet\craftrecranetbooking\records\Organization as OrganizationRecord;

/**
 * Organization element type
 */
class Organization extends Element
{
    public int $organizationId = 0;

    public static function displayName(): string
    {
        return Craft::t('_recranet-booking', 'Organization');
    }

    public static function lowerDisplayName(): string
    {
        return Craft::t('_recranet-booking', 'organization');
    }

    public static function pluralDisplayName(): string
    {
        return Craft::t('_recranet-booking', 'Organizations');
    }

    public static function pluralLowerDisplayName(): string
    {
        return Craft::t('_recranet-booking', 'organizations');
    }

    public static function refHandle(): ?string
    {
        return 'organization';
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
        return true;
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
        return new OrganizationQuery(static::class);
    }

    public static function createCondition(): ElementConditionInterface
    {
        return Craft::createObject(OrganizationCondition::class, [static::class]);
    }

    protected static function defineSources(string $context): array
    {
        return [
            [
                'key' => '*',
                'label' => Craft::t('_recranet-booking', 'All organizations'),
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
            'organizationId' => ['label' => Craft::t('app', 'Recranet Booking organization ID')],
        ];
    }

    protected static function defineDefaultTableAttributes(string $source): array
    {
        return [
            'title',
            'organizationId'
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
        // If organizations should have URLs, define their URI format here
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
        return $user->can('viewOrganizations');
    }

    public function canSave(User $user): bool
    {
        if (parent::canSave($user)) {
            return true;
        }
        // todo: implement user permissions
        return $user->can('saveOrganizations');
    }

    public function canDuplicate(User $user): bool
    {
        if (parent::canDuplicate($user)) {
            return true;
        }
        // todo: implement user permissions
        return $user->can('saveOrganizations');
    }

    public function canDelete(User $user): bool
    {
        if (parent::canSave($user)) {
            return true;
        }
        // todo: implement user permissions
        return $user->can('deleteOrganizations');
    }

    public function canCreateDrafts(User $user): bool
    {
        return true;
    }

    protected function cpEditUrl(): ?string
    {
        return sprintf('recranet-booking/organizations/%s', $this->getCanonicalId());
    }

    public function getPostEditUrl(): ?string
    {
        return UrlHelper::cpUrl('organizations');
    }

    public function prepareEditScreen(Response $response, string $containerId): void
    {
        /** @var Response|CpScreenResponseBehavior $response */
        $response->crumbs([
            [
                'label' => self::pluralDisplayName(),
                'url' => UrlHelper::cpUrl('organizations'),
            ],
        ]);
    }

    public function afterSave(bool $isNew): void
    {
        if (!$this->propagating) {
            $record = OrganizationRecord::findOne($this->id);
            if (!$record) {
                $record = new OrganizationRecord();
                $record->id = $this->id;
            }

            $record->title = $this->title;
            $record->organizationId = $this->organizationId;

            $record->save();
        }

        parent::afterSave($isNew);
    }

    public function afterDelete(): void
    {
        parent::afterDelete();

        Craft::$app->db->createCommand()
            ->delete('{{%_recranet-booking_organizations}}', ['organizationId' => $this->organizationId])
            ->execute();
    }
}
