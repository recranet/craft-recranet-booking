<?php

namespace recranet\craftrecranetbooking\controllers;

use Craft;
use craft\web\Controller;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\web\Response;

/**
 * Settings controller
 */
class SettingsController extends Controller
{
    public $defaultAction = 'index';
    protected array|int|bool $allowAnonymous = self::ALLOW_ANONYMOUS_NEVER;

    public function actionSaveSettings(): ?Response
    {
        $this->requirePostRequest();

        $plugin = RecranetBooking::getInstance();
        $body = Craft::$app->getRequest()->getBodyParams();

        Craft::$app->getPlugins()->savePluginSettings($plugin, [
            'organizationId' => (string) $body['general']['organizationId'] ?? '',
            'bookPageEntry' => isset($body['general']['bookPageEntry'][0]) ? (int) $body['general']['bookPageEntry'][0] : 0,
        ]);

        // Clear cache to ensure settings are updated
        Craft::$app->getCache()->flush();

        Craft::$app->getSession()->setNotice(Craft::t('_recranet-booking', 'Settings saved.'));

        return $this->redirectToPostedUrl();
    }
}
