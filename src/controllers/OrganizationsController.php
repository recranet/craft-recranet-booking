<?php

namespace recranet\craftrecranetbooking\controllers;

use Craft;
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
            $element = Organization::find()->id($elementId)->one();

            if (!$element) {
                throw new NotFoundHttpExceptionAlias('Organization not found');
            }
        } else {
            $element = new Organization();
        }

        $element->title = $request->getBodyParam('title');
        $element->organizationId = (int) $request->getBodyParam('organizationId');

        // Save the element
        if (!Craft::$app->getElements()->saveElement($element)) {
            Craft::$app->getSession()->setError(Craft::t('_recranet-booking', 'Could not save organization.'));

            Craft::$app->getUrlManager()->setRouteParams([
                'element' => $element,
            ]);

            return null;
        }

        Craft::$app->getSession()->setNotice(Craft::t('_recranet-booking', 'Organization saved.'));

        return $this->redirectToPostedUrl($element);
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
}
