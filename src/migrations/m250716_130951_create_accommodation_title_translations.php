<?php

namespace recranet\craftrecranetbooking\migrations;

use craft\db\Migration;

/**
 * m250716_130951_create_accommodation_title_translations migration.
 */
class m250716_130951_create_accommodation_title_translations extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->addColumn('{{%_recranet-booking_accommodations}}', 'titleDe', $this->string());
        $this->addColumn('{{%_recranet-booking_accommodations}}', 'titleEn', $this->string());
        $this->addColumn('{{%_recranet-booking_accommodations}}', 'titleFr', $this->string());

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m250716_130951_create_accommodation_title_translations cannot be reverted.\n";

        return false;
    }
}
