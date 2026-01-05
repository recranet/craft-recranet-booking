<?php

namespace recranet\craftrecranetbooking\migrations;

use Craft;
use craft\base\Field;
use craft\db\Migration;
use craft\db\Query;
use craft\elements\GlobalSet;
use craft\fieldlayoutelements\CustomField;
use craft\helpers\App;
use craft\helpers\StringHelper;
use craft\models\FieldLayout;
use craft\models\FieldLayoutTab;
use recranet\craftrecranetbooking\elements\Accommodation;
use recranet\craftrecranetbooking\elements\AccommodationCategory;
use recranet\craftrecranetbooking\elements\Facility;
use recranet\craftrecranetbooking\elements\LocalityCategory;
use recranet\craftrecranetbooking\elements\Organization as OrganizationElement;
use recranet\craftrecranetbooking\elements\PackageSpecificationCategory;
use recranet\craftrecranetbooking\fields\OrganizationDropdown;
use recranet\craftrecranetbooking\RecranetBooking;

/**
 * m251119_143706_add_organization_to_entities migration.
 */
class m251119_133055_add_organization extends Migration
{
    public const ENTITIES = [
        'accommodations' => Accommodation::class,
        'accommodation_categories' => AccommodationCategory::class,
        'facilities' => Facility::class,
        'locality_categories' => LocalityCategory::class,
        'package_specification_categories' => PackageSpecificationCategory::class,
    ];

    private ?int $defaultOrganizationId = null;

    public function safeUp(): bool
    {
        $this->createTable('{{%_recranet-booking_organizations}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer(),
            'bookPageEntry' => $this->integer(),
            'bookPageEntryTemplate' => $this->string(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $organizationId = (int) App::parseEnv(RecranetBooking::getInstance()->getSettings()->organizationId);
        $bookPageEntry = RecranetBooking::getInstance()->getSettings()->bookPageEntry;
        $bookPageEntryTemplate = RecranetBooking::getInstance()->getSettings()->bookPageEntryTemplate;

        $this->configureGlobalSet();

        if ($organizationId) {
            $this->insert('{{%_recranet-booking_organizations}}', [
                'title' => 'Default Organization',
                'recranetBookingId' => $organizationId,
                'bookPageEntry' => $bookPageEntry,
                'bookPageEntryTemplate' => $bookPageEntryTemplate,
                'dateCreated' => date('Y-m-d H:i:s'),
                'dateUpdated' => date('Y-m-d H:i:s'),
                'uid' => StringHelper::UUID(),
            ]);

            $defaultSite = Craft::$app->getSites()->getPrimarySite();

            $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $defaultSite->id);
            $globalSet->setFieldValue('organizationId', $organizationId);
        }

        foreach (array_keys(self::ENTITIES) as $entity) {
            $this->updateEntity($entity, $organizationId);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $defaultSite = Craft::$app->getSites()->getPrimarySite();
        $defaultSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $defaultSite->id);
        Craft::$app->getGlobals()->deleteSet($defaultSet);
        $this->defaultOrganizationId = $defaultSet?->getFieldValue('organizationId');

        foreach (Craft::$app->getSites()->getAllSites() as $site) {
            if ($site->getId() === $defaultSite->getId()) {
                continue;
            }

            $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $site->id);

            if (!$globalSet) {
                continue;
            }

            $organizationId = $globalSet->getFieldValue('organizationId');

            if (!$organizationId) {
                continue;
            }

            $organization = OrganizationElement::findOne()->recranetBookingId = $organizationId;

            foreach (self::ENTITIES as $entity) {
                $this->deleteEntities($entity, $organizationId);
            }

            Craft::$app->getGlobals()->deleteSet($globalSet);
            Craft::$app->elements->deleteElement($organization);
        }

        if ($defaultSet) {
            Craft::$app->getGlobals()->deleteSet($defaultSet);
        }

        foreach (array_keys(self::ENTITIES) as $entity) {
            $this->dropForeignKeyIfExists("{{%_recranet-booking_$entity}}", 'organizationId');
            $this->dropColumn("{{%_recranet-booking_$entity}}", 'organizationId');
        }

        $this->dropTableIfExists('{{%_recranet-booking_organizations}}');

        return true;
    }

    protected function afterDown(): void
    {
        if ($this->defaultOrganizationId) {
            $defaultOrganization = OrganizationElement::findOne(['recranetBookingId' => $this->defaultOrganizationId]);
            RecranetBooking::getInstance()->getSettings()->organizationId = $defaultOrganization->recranetBookingId;
            RecranetBooking::getInstance()->getSettings()->bookPageEntry = $defaultOrganization->getBookPageEntry()->getId();
            RecranetBooking::getInstance()->getSettings()->bookPageEntryTemplate = $defaultOrganization->getBookPageEntryTemplate();
        }
    }

    private function configureGlobalSet()
    {
        $fieldsService = Craft::$app->fields;

        $field = $fieldsService->getFieldByHandle('organizationId');
        if (!$field) {
            $field = new OrganizationDropdown([
                'name' => 'Organization',
                'handle' => 'organizationId',
                'translationMethod' => Field::TRANSLATION_METHOD_SITE,
            ]);

            $fieldsService->saveField($field);
        }

        foreach (Craft::$app->getSites()->getAllSites() as $site) {
            Craft::$app->getGlobals()->saveSet(new GlobalSet([
                'name' => 'Site organization',
                'handle' => 'siteOrganization',
            ]));

            $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $site->id);

            $fieldLayout = new FieldLayout(['type' => GlobalSet::class]);
            $fieldsService->saveLayout($fieldLayout);

            $tab = new FieldLayoutTab([
                'name' => 'Content',
                'layout' => $fieldLayout,
            ]);

            $tab->setElements([new CustomField($field)]);

            $fieldLayout->setTabs([$tab]);

            $globalSet->setFieldLayout($fieldLayout);

            Craft::$app->getGlobals()->saveSet($globalSet);
        }
    }

    /**
     * @param string $entity
     * @param string|null $organizationId
     * @return void
     */
    private function updateEntity(string $entity, ?string $organizationId): void
    {
        $hasExistingData = (new Query())
            ->from("{{%_recranet-booking_$entity}}")
            ->exists()
        ;

        $this->addColumn("{{%_recranet-booking_$entity}}", 'organizationId', $this->integer()->null());

        if ($hasExistingData && $organizationId) {
            $this->update(
                "{{%_recranet-booking_$entity}}",
                ['organizationId' => $organizationId],
                ['organizationId' => null]
            );
        }

        $this->addForeignKey(
            "{$entity}_organizationId",
            "{{%_recranet-booking_$entity}}",
            'organizationId',
            '{{%_recranet-booking_organizations}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    private function deleteEntities(string $entity, string $organizationId)
    {
        foreach ($entity::findAll(['recranetBookingId' => $organizationId]) as $entity) {
            Craft::$app->elements->deleteElement($entity);
        }
    }
}
