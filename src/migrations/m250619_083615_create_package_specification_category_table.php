<?php

namespace recranet\craftrecranetbooking\migrations;

use craft\db\Migration;

/**
 * m250619_083615_create_package_specification_category_table migration.
 */
class m250619_083615_create_package_specification_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->createTable('{{%_recranet-booking_package_specification_categories}}', [
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
        $this->dropTableIfExists('{{%_recranet-booking_package_specification_categories}}');
        return false;
    }
}
