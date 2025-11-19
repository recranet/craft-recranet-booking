<?php

namespace recranet\craftrecranetbooking;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Elements;
use craft\services\Fields;
use craft\web\UrlManager;
use craft\web\View;
use craft\web\twig\variables\CraftVariable;
use recranet\craftrecranetbooking\elements\Accommodation;
use recranet\craftrecranetbooking\elements\AccommodationCategory;
use recranet\craftrecranetbooking\elements\Facility;
use recranet\craftrecranetbooking\elements\LocalityCategory;
use recranet\craftrecranetbooking\elements\Organization;
use recranet\craftrecranetbooking\elements\PackageSpecificationCategory;
use recranet\craftrecranetbooking\elements\db\AccommodationQuery;
use recranet\craftrecranetbooking\fields\AccommodationCategorySelect;
use recranet\craftrecranetbooking\fields\AccommodationSelect;
use recranet\craftrecranetbooking\fields\FacilitySelect;
use recranet\craftrecranetbooking\fields\LocalityCategorySelect;
use recranet\craftrecranetbooking\fields\PackageSpecificationCategorySelect;
use recranet\craftrecranetbooking\models\Settings;
use recranet\craftrecranetbooking\services\Accommodation as AccommodationService;
use recranet\craftrecranetbooking\services\AccommodationCategory as AccommodationCategoryService;
use recranet\craftrecranetbooking\services\Facility as FacilityService;
use recranet\craftrecranetbooking\services\Import;
use recranet\craftrecranetbooking\services\LocalityCategory as LocalityCategoryService;
use recranet\craftrecranetbooking\services\PackageSpecificationCategory as PackageSpecificationCategoryService;
use recranet\craftrecranetbooking\services\RecranetBookingClient;
use recranet\craftrecranetbooking\variables\RecranetBookingVariable;
use yii\base\Event;

/**
 * Recranet Booking plugin
 *
 * @method static RecranetBooking getInstance()
 * @method Settings getSettings()
 * @property-read RecranetBookingClient $recranetBookingClient
 * @property-read Import $import
 * @property-read AccommodationService $accommodationService
 * @property-read AccommodationCategoryService $accommodationCategoryService
 * @property-read FacilityService $facilityService
 * @property-read LocalityCategoryService $localityCategoryService
 * @property-read PackageSpecificationCategoryService $packageSpecificationCategoryService
 */
class RecranetBooking extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;
    public bool $hasCpSection = true;

    public static function config(): array
    {
        return [
            'components' => [
                'recranetBookingClient' => RecranetBookingClient::class,
                'import' => Import::class,
                'accommodationService' => AccommodationService::class,
                'accommodationCategoryService' => AccommodationCategoryService::class,
                'facilityService' => FacilityService::class,
                'localityCategoryService' => LocalityCategoryService::class,
                'packageSpecificationCategoryService' => PackageSpecificationCategoryService::class
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        $this->attachEventHandlers();
        $this->_registerTemplateRoots();
        $this->_registerElementTypes();
        $this->_registerTwigVariables();
        $this->_registerFields();
        $this->_registerSiteUrlRules();

        if ($this->getSettings()->sitemapEnabled) {
            $this->_registerSitemapUrlRule();
        }
    }

    public function getCpNavItem(): ?array
    {
        $navItem = parent::getCpNavItem();

        $showCp = Craft::$app->config->getConfigFromFile('_recranet-booking')['showCpNav'] ?? true;

        if (!$showCp) {
            return null;
        }

        $navItem['label'] = Craft::t('_recranet-booking', 'Recranet Booking');
        $navItem['url'] = 'recranet-booking/accommodations';
        $navItem['icon'] = '@recranet/craftrecranetbooking/icon-dashboard.svg';
        $navItem['subnav'] = [
            'organizations' => [
                'url' => 'recranet-booking/organizations',
                'badgeCount' => Organization::find()->count(),
                'label' => Craft::t('_recranet-booking', 'Organizations'),
            ],
            'accommodations' => [
                'url' => 'recranet-booking/accommodations',
                'badgeCount' => Accommodation::find()->count(),
                'label' => Craft::t('_recranet-booking', 'Stays'),
            ],
            'facilities' => [
                'url' => 'recranet-booking/facilities',
                'badgeCount' => Facility::find()->count(),
                'label' => Craft::t('_recranet-booking', 'Facilities')
            ],
            'accommodation-categories' => [
                'url' => 'recranet-booking/accommodation-categories',
                'badgeCount' => AccommodationCategory::find()->count(),
                'label' => Craft::t('_recranet-booking', 'Types')
            ],
            'locality-categories' => [
                'url' => 'recranet-booking/locality-categories',
                'badgeCount' => LocalityCategory::find()->count(),
                'label' => Craft::t('_recranet-booking', 'Localities')
            ],
            'package-specification-categories' => [
                'url' => 'recranet-booking/package-specification-categories',
                'badgeCount' => PackageSpecificationCategory::find()->count(),
                'label' => Craft::t('_recranet-booking', 'Package groups')
            ],
            'settings' => [
                'url' => 'recranet-booking/settings',
                'label' => Craft::t('_recranet-booking', 'Settings')
            ],
        ];

        return $navItem;
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('_recranet-booking/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        Craft::setAlias('@recranet/craftrecranetbooking', __DIR__);

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {
            $event->rules['recranet-booking'] = ['template' => '_recranet-booking/_index.twig'];
            $event->rules['recranet-booking/accommodations'] = ['template' => '_recranet-booking/accommodations/_index.twig'];
            $event->rules['recranet-booking/facilities'] = ['template' => '_recranet-booking/facilities/_index.twig'];
            $event->rules['recranet-booking/accommodation-categories'] = ['template' => '_recranet-booking/accommodation-categories/_index.twig'];
            $event->rules['recranet-booking/locality-categories'] = ['template' => '_recranet-booking/locality-categories/_index.twig'];
            $event->rules['recranet-booking/settings'] = ['template' => '_recranet-booking/_settings.twig'];
            $event->rules['actions/_recranet-booking/settings/save-settings'] = '_recranet-booking/settings/save-settings';
            $event->rules['recranet-booking/package-specification-categories'] = ['template' => '_recranet-booking/package-specification-categories/_index.twig'];
            $event->rules['recranet-booking/organizations'] = ['template' => '_recranet-booking/organizations/_index.twig'];
            $event->rules['recranet-booking/organizations/new'] = '_recranet-booking/organizations/edit';
            $event->rules['recranet-booking/organizations/<elementId:\d+>'] = '_recranet-booking/organizations/edit';
        });
    }

    private function _registerSitemapUrlRule(): void
    {
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function (RegisterUrlRulesEvent $event) {
            $event->rules['sitemap-accommodations.xml'] = '_recranet-booking/sitemap';
        });
    }

    private function _registerSiteUrlRules(): void
    {

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $pageBook = RecranetBooking::getInstance()->getSettings()->getBookPageEntry();

                $event->rules[$pageBook->uri . '/<slug:[^/]+>'] = '_recranet-booking/accommodations-routing/accommodation';
                $event->rules[$pageBook->uri . '/<slug:[^/]+>/<page:[^/]+>'] = '_recranet-booking/accommodations-routing';
            }
        );
    }

    private function _registerTemplateRoots(): void
    {
        Event::on(View::class, View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS, function(RegisterTemplateRootsEvent $event) {
            $event->roots[$this->id] = $this->getBasePath() . DIRECTORY_SEPARATOR . 'templates';
        });
    }

    private function _registerFields(): void
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = FacilitySelect::class;
            $event->types[] = LocalityCategorySelect::class;
            $event->types[] = AccommodationCategorySelect::class;
            $event->types[] = AccommodationSelect::class;
            $event->types[] = PackageSpecificationCategorySelect::class;
        });
    }

    private function _registerTwigVariables(): void
    {
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $event->sender->set('recranetBooking', RecranetBookingVariable::class);
                $event->sender->set('accommodations', AccommodationQuery::class);
            }
        );
    }

    private function _registerElementTypes(): void
    {
        Event::on(Elements::class, Elements::EVENT_REGISTER_ELEMENT_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = Accommodation::class;
            $event->types[] = AccommodationCategory::class;
            $event->types[] = LocalityCategory::class;
            $event->types[] = Facility::class;
            $event->types[] = Organization::class;
        });
    }
}
