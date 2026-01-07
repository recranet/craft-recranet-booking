<?php

namespace recranet\craftrecranetbooking\migrations;

use Craft;
use craft\base\Field;
use craft\db\Migration;
use craft\elements\GlobalSet;
use craft\fieldlayoutelements\CustomField;
use craft\models\FieldLayout;
use craft\models\FieldLayoutTab;
use recranet\craftrecranetbooking\fields\OrganizationDropdown;

/**
 * Install migration.
 */
class Install extends Migration
{
    public const ENTITIES = [
        'accommodations',
        'accommodation_categories',
        'facilities',
        'locality_categories',
        'package_specification_categories',
    ];

    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->createTable('{{%_recranet-booking_facilities}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
            'organizationId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createTable('{{%_recranet-booking_accommodation_categories}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
            'organizationId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createTable('{{%_recranet-booking_locality_categories}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
            'organizationId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createTable('{{%_recranet-booking_package_specification_categories}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
            'organizationId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createTable('{{%_recranet-booking_accommodations}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'slugEn' => $this->string(),
            'slugDe' => $this->string(),
            'slugFr' => $this->string(),
            'titleEn' => $this->string(),
            'titleDe' => $this->string(),
            'titleFr' => $this->string(),
            'recranetBookingId' => $this->integer()->notNull(),
            'organizationId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createTable('{{%_recranet-booking_organizations}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
            'bookPageEntry' => $this->integer()->notNull(),
            'bookPageEntryTemplate' => $this->string()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        foreach (self::ENTITIES as $entity) {
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

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization');
        if ($globalSet) {
            Craft::$app->getGlobals()->deleteSet($globalSet);
        }

        $entities = self::ENTITIES;
        $entities[] = 'organizations';

        foreach ($entities as $entity) {
            $this->dropAllForeignKeysToTable("{{%_recranet-booking_$entity}}");
        }

        foreach ($entities as $entity) {
            $this->dropTableIfExists("{{%_recranet-booking_$entity}}");
        }

        return true;
    }
}
