<?php

namespace recranet\craftrecranetbooking\fields;

use craft\fields\BaseRelationField;
use recranet\craftrecranetbooking\RecranetBooking;

abstract class RecranetSelectWithOrganizationFilter extends BaseRelationField
{
    public function getInputSelectionCriteria(): array
    {
        $criteria = parent::getInputSelectionCriteria();

        // Get the current site's organization
        $organizationService = RecranetBooking::getInstance()->getOrganizationService();
        $organization = $organizationService->getOrganizationBySite();

        // If an organization is configured for this site, filter by it
        if ($organization !== null) {
            $criteria['organizationId'] = $organization->id;
        }

        return $criteria;
    }
}