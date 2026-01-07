<?php
namespace recranet\craftrecranetbooking\controllers;

use craft\web\Controller;
use recranet\craftrecranetbooking\RecranetBooking;
use yii\web\Response;

class AccommodationsRoutingController extends Controller
{
    public $defaultAction = 'index';
    protected array|int|bool $allowAnonymous = true;

    public function actionIndex(string|null $slug, string|null $page): Response
    {
        $organization = RecranetBooking::getInstance()->organizationService->getOrganizationBySite();
        $bookPage = $organization?->getBookPageEntry();
        $bookPageEntryTemplate = $organization?->getBookPageEntryTemplate();

        if (!$bookPage || !$bookPageEntryTemplate) {
            return $this->asJson('Failed to find the booking page entry.');
        }

        return $this->renderTemplate($bookPageEntryTemplate, [
            'slug' => $slug ?? null,
            'page' => $page ?? null,
            'entry' => $bookPage,
        ]);
    }

    public function actionAccommodation(string $slug): Response
    {
        $organization = RecranetBooking::getInstance()->organizationService->getOrganizationBySite();
        $bookPage = $organization?->getBookPageEntry();
        $bookPageEntryTemplate = $organization?->getBookPageEntryTemplate();

        if (!$bookPage || !$bookPageEntryTemplate) {
            return $this->asJson('Failed to find the booking page entry.');
        }

        return $this->renderTemplate($bookPageEntryTemplate, [
            'slug' => $slug,
            'entry' => $bookPage,
        ]);
    }
}
