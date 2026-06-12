<?php

namespace recranet\craftrecranetbooking\migrations;

use craft\db\Migration;

/**
 * m260612_000000_add_accommodation_listings migration.
 */
class m260612_000000_add_accommodation_listings extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable('{{%_recranet_booking_accommodation_listings}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'locale' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
            'organizationId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->addForeignKey(
            'accommodation_listings_organizationId',
            '{{%_recranet_booking_accommodation_listings}}',
            'organizationId',
            '{{%_recranet_booking_organizations}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable('{{%_recranet_booking_accommodation_listing_accommodations}}', [
            'listingId' => $this->integer()->notNull(),
            'accommodationId' => $this->integer()->notNull(),
            'PRIMARY KEY([[listingId]], [[accommodationId]])',
        ]);

        $this->addForeignKey(
            'accommodation_listing_accommodations_listingId',
            '{{%_recranet_booking_accommodation_listing_accommodations}}',
            'listingId',
            '{{%_recranet_booking_accommodation_listings}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'accommodation_listing_accommodations_accommodationId',
            '{{%_recranet_booking_accommodation_listing_accommodations}}',
            'accommodationId',
            '{{%_recranet_booking_accommodations}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropAllForeignKeysToTable('{{%_recranet_booking_accommodation_listing_accommodations}}');
        $this->dropTableIfExists('{{%_recranet_booking_accommodation_listing_accommodations}}');

        $this->dropAllForeignKeysToTable('{{%_recranet_booking_accommodation_listings}}');
        $this->dropTableIfExists('{{%_recranet_booking_accommodation_listings}}');

        return true;
    }
}
