<?php

namespace recranet\craftrecranetbooking\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Query;
use craft\helpers\StringHelper;

/**
 * m251119_143706_add_organization_to_accommodations migration.
 */
class m251119_143706_add_organization_to_accommodations extends Migration
{
    public function safeUp(): bool
    {
        // Check if there are existing accommodations
        $hasExistingData = (new Query())
            ->from('{{%_recranet-booking_accommodations}}')
            ->exists();

        $defaultOrgId = null;

        if ($hasExistingData) {
            // Create a default organization for existing accommodations
            $this->insert('{{%_recranet-booking_organizations}}', [
                'title' => 'Default Organization',
                'organizationId' => 0,
                'dateCreated' => date('Y-m-d H:i:s'),
                'dateUpdated' => date('Y-m-d H:i:s'),
                'uid' => StringHelper::UUID(),
            ]);

            // Get the auto-incremented ID
            $defaultOrgId = $this->db->getLastInsertID('{{%_recranet-booking_organizations}}');
        }

        // Add column as nullable first to avoid constraint violation
        $this->addColumn('{{%_recranet-booking_accommodations}}', 'organizationId', $this->integer()->null());

        // Update existing rows with the default organization ID
        if ($hasExistingData && $defaultOrgId) {
            $this->update(
                '{{%_recranet-booking_accommodations}}',
                ['organizationId' => $defaultOrgId],
                ['organizationId' => null]
            );
        }

        // Now make the column NOT NULL
        $this->alterColumn('{{%_recranet-booking_accommodations}}', 'organizationId', $this->integer()->notNull());

        // Add the foreign key constraint
        $this->addForeignKey(
            'organizationId',
            '{{%_recranet-booking_accommodations}}',
            'organizationId',
            '{{%_recranet-booking_organizations}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropForeignKey('organizationId', '{{%_recranet-booking_accommodations}}');
        $this->dropColumn('{{%_recranet-booking_accommodations}}', 'organizationId');

        // Optionally remove the default organization (organizationId = 0) if it exists
        $this->delete('{{%_recranet-booking_organizations}}', ['organizationId' => 0]);

        return false;
    }
}
