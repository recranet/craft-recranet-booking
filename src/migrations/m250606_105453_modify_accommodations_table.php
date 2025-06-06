<?php

namespace recranet\craftrecranetbooking\migrations;

use Craft;
use craft\db\Migration;

/**
 * m250606_105453_modify_accommodations_table migration.
 */
class m250606_105453_modify_accommodations_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->addColumn('{{%_recranet-booking_accommodations}}', 'slug', $this->string()->notNull()->after('title'));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m250606_105453_modify_accommodations_table cannot be reverted.\n";
        return false;
    }
}
