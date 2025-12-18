<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\elements\Entry;
use craft\helpers\App;
use craft\models\Site;
use yii\base\Component;
use recranet\craftrecranetbooking\elements\Organization as OrganizationElement;

/**
 * Organization service
 */
class Organization extends Component
{
    public function deleteAll() : void
    {
        $organizations = OrganizationElement::findAll();

        foreach ($organizations as $organization) {
            try {
                Craft::$app->elements->deleteElement($organization);
            } catch (\Throwable $e) {
                Craft::error("Failed to delete accommodation with ID {$organization->id}: " . $e->getMessage(), __METHOD__);
            }
        }
    }

    public function getOrganizationBySite(Site $site = null): ?OrganizationElement
    {
        if ($site === null) {
            $site = Craft::$app->getSites()->getCurrentSite();
        }

        $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $site->id);

        $organizationId = $globalSet?->getFieldValue('organizationId');

        return OrganizationElement::find()->id($organizationId)->one();
    }

    public function getBookPageEntryBySite(Site $site = null): ?Entry
    {
        $organization = $this->getOrganizationBySite($site);

        return $organization?->getBookPageEntry();
    }
}
