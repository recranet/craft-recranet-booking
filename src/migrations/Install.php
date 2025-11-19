<?php

namespace recranet\craftrecranetbooking\migrations;

use craft\db\Migration;

/**
 * Install migration.
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->createTable('{{%_recranet-booking_facilities}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createTable('{{%_recranet-booking_accommodation_categories}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createTable('{{%_recranet-booking_locality_categories}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createTable('{{%_recranet-booking_package_specification_categories}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
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
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createTable('{{%_recranet-booking_organizations}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'organizationId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropTableIfExists('{{%_recranet-booking_facilities}}');
        $this->dropTableIfExists('{{%_recranet-booking_accommodations}}');
        $this->dropTableIfExists('{{%_recranet-booking_accommodation_categories}}');
        $this->dropTableIfExists('{{%_recranet-booking_locality_categories}}');
        $this->dropTableIfExists('{{%_recranet-booking_package_specification_categories}}');
        $this->dropTableIfExists('{{%_recranet-booking_organizations}}');
        return true;
    }
}
