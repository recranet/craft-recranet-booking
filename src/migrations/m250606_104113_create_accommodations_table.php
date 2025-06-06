<?php

namespace recranet\craftrecranetbooking\migrations;

use Craft;
use craft\db\Migration;

/**
 * m250606_104113_create_accommodations_table migration.
 */
class m250606_104113_create_accommodations_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->createTable('{{%_recranet-booking_accommodations}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'recranetBookingId' => $this->integer()->notNull(),
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
        $this->dropTableIfExists('{{%_recranet-booking_accommodations}}');
        return true;
    }
}
