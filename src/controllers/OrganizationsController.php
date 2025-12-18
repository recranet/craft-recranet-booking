<?php

namespace recranet\craftrecranetbooking\controllers;

use Craft;
use craft\helpers\App;
use craft\web\Controller;
use recranet\craftrecranetbooking\elements\Organization;
use yii\web\Response;
use yii\web\NotFoundHttpException as NotFoundHttpExceptionAlias;

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
                throw new NotFoundHttpExceptionAlias('Organization not found');
            }
        }

        if (!$element) {
            $element = new Organization();
        }

        $variables = [
            'sites' => $this->getSites(),
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
                throw new NotFoundHttpExceptionAlias('Organization not found');
            }
        } else {
            $organization = new Organization();
        }

        $organization->title = $request->getBodyParam('title');
        $organization->recranetBookingId = (int) $request->getBodyParam('recranetBookingId');
        $organization->bookPageEntry = (int) $request->getBodyParam('bookPageEntry');
        $organization->bookPageEntryTemplate = $request->getBodyParam('bookPageEntryTemplate');

        $site = Craft::$app->getSites()->getCurrentSite();
        $globalSet = Craft::$app->getGlobals()->getSetByHandle('siteOrganization', $site->id);
        $globalSet?->setFieldValue('organizationId', $organization->getId());

        // Save the element
        if (!Craft::$app->getElements()->saveElement($organization)) {
            Craft::$app->getSession()->setError(Craft::t('_recranet-booking', 'Could not save organization.'));

            Craft::$app->getUrlManager()->setRouteParams([
                'element' => $organization,
            ]);

            return null;
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
            throw new NotFoundHttpExceptionAlias('Organization not found');
        }

        if (!Craft::$app->getElements()->deleteElement($element)) {
            Craft::$app->getSession()->setError(Craft::t('_recranet-booking', 'Could not delete organization.'));
            return $this->asJson(['success' => false]);
        }

        Craft::$app->getSession()->setNotice(Craft::t('_recranet-booking', 'Organization deleted.'));
        return $this->asJson(['success' => true]);
    }

    /**
     * Retrieves all sites in an id, name pair, suitable for the underlying options display.
     */
    private function getSites()
    {
        $sites = [];

        foreach (Craft::$app->getSites()->getAllSites() as $site) {
            $sites[$site->uid] = Craft::t('site', $site->name);
        }

        return $sites;
    }
}
