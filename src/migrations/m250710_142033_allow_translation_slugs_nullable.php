<?php

namespace recranet\craftrecranetbooking\migrations;

use Craft;
use craft\db\Migration;

/**
 * m250710_142033_allow_translation_slugs_nullable migration.
 */
class m250710_142033_allow_translation_slugs_nullable extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->alterColumn('_recranet-booking_accommodations', 'slugEn', $this->string()->null());
        $this->alterColumn('_recranet-booking_accommodations', 'slugFr', $this->string()->null());
        $this->alterColumn('_recranet-booking_accommodations', 'slugDe', $this->string()->null());

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->alterColumn('_recranet-booking_accommodations', 'slugEn', $this->string()->notNull());
        $this->alterColumn('_recranet-booking_accommodations', 'slugFr', $this->string()->notNull());
        $this->alterColumn('_recranet-booking_accommodations', 'slugDe', $this->string()->notNull());

        return false;
    }
}
