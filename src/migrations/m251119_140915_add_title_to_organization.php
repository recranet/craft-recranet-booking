<?php

namespace recranet\craftrecranetbooking\migrations;

use Craft;
use craft\db\Migration;

/**
 * m251119_140915_add_title_to_organization migration.
 */
class m251119_140915_add_title_to_organization extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->addColumn('{{%_recranet-booking_organizations}}', 'title', $this->string()->notNull());

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropColumn('{{%_recranet_booking_organizations}}', 'title');
        return false;
    }
}
