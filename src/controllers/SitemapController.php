<?php

namespace recranet\craftrecranetbooking\controllers;

use Craft;
use craft\elements\Entry;
use craft\web\Controller;
use recranet\craftrecranetbooking\elements\Accommodation;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\web\Response;

/**
 * Sitemap controller
 */
class SitemapController extends Controller
{
    public $defaultAction = 'index';
    protected array|int|bool $allowAnonymous = true;

    /**
     * _recranet-booking/sitemap action
     */
    public function actionIndex(): Response
    {
        $organizationService = RecranetBooking::$organizationService;
        $currentSite = Craft::$app->getSites()->getCurrentSite();

        $accommodations = Accommodation::find()->where(['organizationId' => $organizationService->getOrganizationBySite()->getId()])->all();
        $bookPage = $organizationService->getOrganizationBySite()->getBookPageEntry();

        $bookPageEntry = Entry::find()
            ->section('*')
            ->site($currentSite)
            ->id($bookPage->id)
            ->one();

        if (!$bookPageEntry) {
            return $this->asJson('Failed to find the booking page entry.');
        }

        Craft::$app->response->format = Response::FORMAT_RAW;
        Craft::$app->response->headers->set('Content-Type', 'application/xml');

        return $this->renderTemplate('_recranet-booking/sitemap/_accommodations', [
            'accommodations' => $accommodations,
            'baseUrl' => $bookPageEntry,
        ]);
    }
}
