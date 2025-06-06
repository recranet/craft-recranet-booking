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

        $settings = $plugin->getSettings();

        $settings->organizationId = !empty($body['general']['organizationId']) ? (int) $body['general']['organizationId'] : null;
        $settings->bookPageEntry = isset($body['general']['bookPageEntry'][0]) ? (int) $body['general']['bookPageEntry'][0] : null;
        $settings->sitemapEnabled = (bool) ($body['sitemap']['sitemapEnabled'] ?? false);

        if (!$settings->validate()) {
            Craft::$app->getSession()->setError(Craft::t('app', 'Couldn’t save settings.'));

            return $this->renderTemplate('_recranet-booking/_settings', [
                'settings' => $settings,
            ]);
        }

        Craft::$app->getPlugins()->savePluginSettings($plugin, $settings->toArray());

        // Clear cache to ensure settings are updated
        Craft::$app->getCache()->flush();

        Craft::$app->getSession()->setNotice(Craft::t('_recranet-booking', 'Settings saved.'));

        return $this->redirectToPostedUrl();
    }
}
