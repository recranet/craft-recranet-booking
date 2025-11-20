<?php

namespace recranet\craftrecranetbooking\migrations;

use Craft;
use craft\db\Migration;

/**
 * m251119_133055_add_organization migration.
 */
class m251119_133055_add_organization extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
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
        $this->dropTableIfExists('{{%_recranet-booking_organizations}}');
        return false;
    }
}
