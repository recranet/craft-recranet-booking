<?php

namespace recranet\craftrecranetbooking\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Query;
use craft\elements\GlobalSet;
use recranet\craftrecranetbooking\elements\Organization as OrganizationElement;

/**
 * Repairs organizations that were seeded by m251119_133055_add_organization via a raw table
 * insert instead of a real element save. Their `id` came from the plugin table's own
 * auto-increment, so it can collide with an unrelated element's id (e.g. the admin user) —
 * and deleting the organization then deletes/corrupts whatever element actually owns that id.
 */
class m260701_120000_fix_organization_element_id_collision extends Migration
{
    private const CHILD_TABLES = [
        '{{%_recranet_booking_accommodations}}',
        '{{%_recranet_booking_accommodation_categories}}',
        '{{%_recranet_booking_facilities}}',
        '{{%_recranet_booking_locality_categories}}',
        '{{%_recranet_booking_package_specification_categories}}',
        '{{%_recranet_booking_accommodation_listings}}',
    ];

    public function safeUp(): bool
    {
        $rows = (new Query())->from('{{%_recranet_booking_organizations}}')->all();

        foreach ($rows as $row) {
            $oldId = (int) $row['id'];

            $elementType = (new Query())
                ->select(['type'])
                ->from('{{%elements}}')
                ->where(['id' => $oldId])
                ->scalar();

            if ($elementType === OrganizationElement::class) {
                // Already backed by a real Organization element, nothing to fix.
                continue;
            }

            $organization = new OrganizationElement([
                'title' => $row['title'],
                'recranetBookingId' => $row['recranetBookingId'],
                'bookPageEntry' => $row['bookPageEntry'],
                'bookPageEntryTemplate' => $row['bookPageEntryTemplate'],
            ]);

            if (!Craft::$app->getElements()->saveElement($organization)) {
                throw new \Exception("Could not recreate organization element for id $oldId");
            }

            $newId = $organization->getId();

            foreach (self::CHILD_TABLES as $table) {
                $this->update($table, ['organizationId' => $newId], ['organizationId' => $oldId]);
            }

            $this->repointGlobalSetReferences($oldId, $newId);

            $this->delete('{{%_recranet_booking_organizations}}', ['id' => $oldId]);

            echo "    > recreated organization '{$row['title']}' as element #$newId"
                . " (old id #$oldId collided with " . ($elementType ?: 'no element') . ")\n";
        }

        return true;
    }

    public function safeDown(): bool
    {
        echo "This migration needs no revert, as it fixes a earlier migration, that has been updated already.\n";

        return false;
    }

    private function repointGlobalSetReferences(int $oldId, int $newId): void
    {
        $globalSetIds = (new Query())
            ->select(['id'])
            ->from('{{%globalsets}}')
            ->where(['handle' => 'siteOrganization'])
            ->column();

        foreach ($globalSetIds as $globalSetId) {
            foreach (Craft::$app->getSites()->getAllSites() as $site) {
                $globalSet = Craft::$app->getElements()->getElementById($globalSetId, GlobalSet::class, $site->id);

                if (!$globalSet) {
                    continue;
                }

                $behavior = $globalSet->getBehavior('customFields');

                if (
                    !$behavior
                    || !$behavior->canGetProperty('organizationId')
                    || (int) (string) $globalSet->getFieldValue('organizationId') !== $oldId
                ) {
                    continue;
                }

                $globalSet->setFieldValue('organizationId', $newId);
                Craft::$app->getElements()->saveElement($globalSet);
            }
        }
    }
}
