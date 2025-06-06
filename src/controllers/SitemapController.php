<?php

namespace recranet\craftrecranetbooking\controllers;

use Craft;
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
        $accommodations = Accommodation::find()->all();

        Craft::$app->response->format = Response::FORMAT_RAW;
        Craft::$app->response->headers->set('Content-Type', 'application/xml');

        return $this->renderTemplate('_recranet-booking/sitemap/_accommodations', [
            'accommodations' => $accommodations,
            'baseUrl' => RecranetBooking::getInstance()->getSettings()->getBookPageEntry(),
        ]);
    }
}
