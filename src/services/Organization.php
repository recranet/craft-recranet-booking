<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\elements\Entry;
use craft\models\Site;
use Iterator;
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
                Craft::error("Failed to delete accommodation with ID $organization->id: " . $e->getMessage(), __METHOD__);
            }
        }
    }

    public function getOrganizationBySite(Site $site = null): ?OrganizationElement
    {
        if ($site === null) {
            $site = Craft::$app->getSites()->getCurrentSite();
        }

        $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $site->id);

        $behavior = $globalSet?->getBehavior('customFields');
        if (!$behavior || !$behavior->canGetProperty('organizationId') || !$behavior->organizationId) {
            return null;
        }

        $organizationId = $behavior->organizationId;

        return OrganizationElement::find()->id($organizationId)->one();
    }

    /**
     * @param OrganizationElement $organization
     * @return Site[]
     */
    public function getSitesByOrganization(OrganizationElement $organization): array
    {
        $sites = [];

        foreach(Craft::$app->getSites()->getAllSites() as $site) {
            $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $site->id);

            $behavior = $globalSet->getBehavior('customFields');
            if (!$behavior->canGetProperty('organizationId') || !$behavior->organizationId) {
                continue;
            }

            $organizationId = $behavior->organizationId;

            if ($organizationId && $organizationId === $organization->id) {
                $sites[] = $site;
            }
        }

        return $sites;
    }

    /**
     * @return Iterator
     */
    public function getLinkedOrganizations(): Iterator
    {
        foreach(Craft::$app->getSites()->getAllSites() as $site) {
            $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $site->id);

            $behavior = $globalSet?->getBehavior('customFields');
            if (!$behavior || !$behavior->canGetProperty('organizationId') || !$behavior->organizationId) {
                continue;
            }

            yield OrganizationElement::find()->id($behavior->organizationId)->one();
        }
    }

    public function getBookPageEntryBySite(Site $site = null): ?Entry
    {
        $organization = $this->getOrganizationBySite($site);

        return $organization?->getBookPageEntry();
    }
}
