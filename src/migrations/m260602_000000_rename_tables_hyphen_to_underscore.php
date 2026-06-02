<?php

namespace recranet\craftrecranetbooking\migrations;

use craft\db\Migration;

/**
 * Renames plugin tables from _recranet-booking_* to _recranet_booking_* to prevent
 * MySQL from interpreting the hyphen as a minus operator in unquoted contexts.
 */
class m260602_000000_rename_tables_hyphen_to_underscore extends Migration
{
    private const HYPHEN_TABLES = [
        '_recranet-booking_facilities',
        '_recranet-booking_accommodation_categories',
        '_recranet-booking_locality_categories',
        '_recranet-booking_package_specification_categories',
        '_recranet-booking_accommodations',
        '_recranet-booking_organizations',
    ];

    public function safeUp(): bool
    {
        foreach (self::HYPHEN_TABLES as $hyphen) {
            $underscore = str_replace('-', '_', $hyphen);
            if ($this->db->tableExists("{{%$hyphen}}")) {
                $this->renameTable("{{%$hyphen}}", "{{%$underscore}}");
            }
        }

        return true;
    }

    public function safeDown(): bool
    {
        foreach (array_reverse(self::HYPHEN_TABLES) as $hyphen) {
            $underscore = str_replace('-', '_', $hyphen);
            if ($this->db->tableExists("{{%$underscore}}")) {
                $this->renameTable("{{%$underscore}}", "{{%$hyphen}}");
            }
        }

        return true;
    }
}
