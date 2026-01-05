<?php

namespace recranet\craftrecranetbooking\services;

use Craft;
use craft\elements\Entry;
use craft\errors\InvalidFieldException;
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

        $organizationId = $globalSet?->getFieldValue('organizationId');

        if (!$organizationId) {
            return null;
        }

        return OrganizationElement::find()->id($organizationId)->one();
    }

    /**
     * @param OrganizationElement $organization
     * @return Site[]
     * @throws InvalidFieldException
     */
    public function getSitesByOrganization(OrganizationElement $organization): array
    {
        $sites = [];

        foreach(Craft::$app->getSites()->getAllSites() as $site) {
            $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $site->id);

            dd();
            if (empty($globalSet->getFieldValues(['organizationId']))) {
                continue;
            }

            $organizationId = $globalSet->getFieldValue('organizationId');

            if ($organizationId && $organizationId === $organization->id) {
                $sites[] = $site;
            }
        }

        return $sites;
    }

    public function getBookPageEntryBySite(Site $site = null): ?Entry
    {
        $organization = $this->getOrganizationBySite($site);

        return $organization?->getBookPageEntry();
    }

    public function getUnlinkedSites(): array
    {
        $unlinkedSites = [];

        foreach (Craft::$app->getSites()->getAllSites() as $site) {
            if (is_null($this->getOrganizationBySite($site))) {
                $unlinkedSites[] = $site;
            }
        }

        return $unlinkedSites;
    }
}
