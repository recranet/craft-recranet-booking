<?php

namespace recranet\craftrecranetbooking\controllers;

use Craft;
use craft\elements\GlobalSet;
use craft\errors\ElementNotFoundException;
use craft\web\Controller;
use recranet\craftrecranetbooking\elements\Organization;
use yii\web\Response;
use recranet\craftrecranetbooking\RecranetBooking;

/**
 * Organization controller
 */
class OrganizationsController extends Controller
{
    protected array|int|bool $allowAnonymous = self::ALLOW_ANONYMOUS_NEVER;

    public function actionEdit(?int $elementId = null, ?Organization $element = null): Response
    {
        if ($elementId) {
            $element = Organization::find()
                ->id($elementId)
                ->one()
            ;

            if (!$element) {
                throw new ElementNotFoundException('Organization not found');
            }
        }

        if (!$element) {
            $element = new Organization();
        }

        $organizationService = RecranetBooking::getInstance()->organizationService;

        $linkedSites = $organizationService->getSitesByOrganization($element);

        $variables = [
            'sites' => $this->getSites(),
            'linkedSites' => $linkedSites,
            'element' => $element,
            'isNew' => !$element->id,
            'continueEditingUrl' => '_recranet-booking/organizations/' . $element->id
        ];

        return $this->renderTemplate('_recranet-booking/organizations/_edit', $variables);
    }

    public function actionSave(): ?Response
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();
        $elementId = $request->getBodyParam('elementId');

        if ($elementId) {
            $organization = Organization::find()->id($elementId)->one();

            if (!$organization) {
                throw new ElementNotFoundException('Organization not found');
            }
        } else {
            $organization = new Organization();
        }

        $organization->title = $request->getBodyParam('title');
        $organization->recranetBookingId = (int) $request->getBodyParam('recranetBookingId');
        $organization->bookPageEntryTemplate = $request->getBodyParam('bookPageEntryTemplate');
        $organization->bookPageEntry = (int) $request->getBodyParam('bookPageEntry')[0] ?? null;

        if (!Craft::$app->getElements()->saveElement($organization)) {
            Craft::$app->getSession()->setError(Craft::t('_recranet-booking', 'Could not save organization.'));

            Craft::$app->getUrlManager()->setRouteParams([
                'element' => $organization,
            ]);

            return null;
        }

        $siteId = $request->getBodyParam('siteId');
        if ($siteId) {
            $this->saveSiteForOrganization($organization, (int) $siteId);
        }

        Craft::$app->getSession()->setNotice(Craft::t('_recranet-booking', 'Organization saved.'));

        return $this->redirectToPostedUrl($organization);
    }

    public function actionDelete(): Response
    {
        $this->requirePostRequest();

        $elementId = Craft::$app->getRequest()->getRequiredBodyParam('elementId');
        $element = Organization::find()->id($elementId)->one();

        if (!$element) {
            throw new ElementNotFoundException('Organization not found');
        }

        if (!Craft::$app->getElements()->deleteElement($element)) {
            Craft::$app->getSession()->setError(Craft::t('_recranet-booking', 'Could not delete organization.'));
            return $this->asJson(['success' => false]);
        }

        Craft::$app->getSession()->setNotice(Craft::t('_recranet-booking', 'Organization deleted.'));
        return $this->asJson(['success' => true]);
    }

    private function getSites()
    {
        $sites = [];

        foreach (Craft::$app->getSites()->getAllSites() as $site) {
            $sites[$site->getId()] = Craft::t('site', $site->name);
        }

        return $sites;
    }

    private function saveSiteForOrganization(Organization $organization, ?int $siteId = null): void
    {
        $site = Craft::$app->getSites()->getSiteById($siteId);
        $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $site->id);

        if (!$globalSet) {
            $globalSet = new GlobalSet([
                'name' => 'Site organization',
                'handle' => 'siteOrganization',
            ]);

            if (!Craft::$app->getGlobals()->saveSet($globalSet)) {
                Craft::$app->getSession()->setError(Craft::t('_recranet-booking', 'Could not create site organization global set.'));

                return;
            }

            $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $site->id);
        }

        $globalSet->setFieldValue('organizationId', $organization->getId());

        if (!Craft::$app->getElements()->saveElement($globalSet)) {
            Craft::error('Could not save global set with organization ID', __METHOD__);
            Craft::$app->getSession()->setError(Craft::t('_recranet-booking', 'Could not link organization to site.'));
        }
    }
}
