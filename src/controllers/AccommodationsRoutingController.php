<?php
namespace recranet\craftrecranetbooking\controllers;

use Craft;
use craft\web\Controller;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\web\Response;

class AccommodationsRoutingController extends Controller
{
    public $defaultAction = 'index';
    protected array|int|bool $allowAnonymous = true;

    public function actionIndex(string|null $slug, string|null $page): Response
    {
        $pageBook = RecranetBooking::getInstance()->getSettings()->getBookPageEntry();

        if (!$pageBook) {
            return $this->asJson('Failed to find the booking page entry.');
        }

        $pageBookEntryTemplate = RecranetBooking::getInstance()->getSettings()->getBookPageEntryTemplate();

        return $this->renderTemplate($pageBookEntryTemplate, [
            'slug' => $slug ?? null,
            'page' => $page ?? null,
            'entry' => $pageBook,
        ]);
    }

    public function actionAccommodation(string $slug): Response
    {
        $pageBook = RecranetBooking::getInstance()->getSettings()->getBookPageEntry();

        if (!$pageBook) {
            return $this->asJson('Failed to find the booking page entry.');
        }

        $pageBookEntryTemplate = RecranetBooking::getInstance()->getSettings()->getBookPageEntryTemplate();

        return $this->renderTemplate($pageBookEntryTemplate, [
            'slug' => $slug,
            'entry' => $pageBook,
        ]);
    }
}
