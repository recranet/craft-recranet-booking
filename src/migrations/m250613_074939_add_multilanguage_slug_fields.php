<?php

namespace recranet\craftrecranetbooking\migrations;

use craft\db\Migration;

/**
 * m250613_074939_add_multilanguage_slug_fields migration.
 */
class m250613_074939_add_multilanguage_slug_fields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->addColumn('{{%_recranet-booking_accommodations}}', 'slugFr', $this->string()->after('slug')->null());
        $this->addColumn('{{%_recranet-booking_accommodations}}', 'slugDe', $this->string()->after('slug')->null());
        $this->addColumn('{{%_recranet-booking_accommodations}}', 'slugEn', $this->string()->after('slug')->null());

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m250613_074939_add_multilanguage_slug_fields cannot be reverted.\n";
        return false;
    }
}
