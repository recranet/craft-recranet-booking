<?php

namespace recranet\craftrecranetbooking;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Elements;
use craft\services\Fields;
use craft\web\UrlManager;
use recranet\craftrecranetbooking\elements\Accommodation;
use recranet\craftrecranetbooking\elements\Facility;
use recranet\craftrecranetbooking\fields\FacilitySelect;
use recranet\craftrecranetbooking\models\Settings;
use recranet\craftrecranetbooking\services\Facility as FacilityAlias;
use recranet\craftrecranetbooking\services\Import;
use recranet\craftrecranetbooking\services\RecranetBookingClient;
use yii\base\Event;

/**
 * Recranet Booking plugin
 *
 * @method static RecranetBooking getInstance()
 * @method Settings getSettings()
 * @property-read RecranetBookingClient $recranetBookingClient
 * @property-read Import $import
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
                'import' => Import::class
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        $this->attachEventHandlers();

        // Any code that creates an element query or loads Twig should be deferred until
        // after Craft is fully initialized, to avoid conflicts with other plugins/modules
        Craft::$app->onInit(function() {
            // ...
        });
    }

    public function getCpNavItem(): ?array
    {
        $navItem = parent::getCpNavItem();

        $navItem['label'] = Craft::t('_recranet-booking', 'Recranet Booking');
        $navItem['url'] = 'recranet-booking';
        $navItem['icon'] = '@recranet/craftrecranetbooking/icon-dashboard.svg';
        $navItem['subnav'] = [
            'accommodations' => [
                'url' => 'recranet-booking/accommodations',
                'badgeCount' => Accommodation::find()->count(),
                'label' => Craft::t('_recranet-booking', 'Accommodations'),
            ],
            'facilities' => [
                'url' => 'recranet-booking/facilities',
                'badgeCount' => Facility::find()->count(),
                'label' => Craft::t('_recranet-booking', 'Facilities'),
            ],
            'settings' => [
                'url' => 'recranet-booking/settings',
                'label' => Craft::t('_recranet-booking', 'Settings'),
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

        Event::on(Elements::class, Elements::EVENT_REGISTER_ELEMENT_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = Facility::class;
        });
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {
            $event->rules['recranet-booking'] = ['template' => '_recranet-booking/_index.twig'];
            $event->rules['recranet-booking/accommodations'] = ['template' => '_recranet-booking/accommodations/_index.twig'];
            $event->rules['recranet-booking/facilities'] = ['template' => '_recranet-booking/facilities/_index.twig'];
            $event->rules['recranet-booking/settings'] = ['template' => '_recranet-booking/_settings.twig'];
            $event->rules['actions/_recranet-booking/settings/save-settings'] = '_recranet-booking/settings/save-settings';
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = FacilitySelect::class;
        });
        Event::on(Elements::class, Elements::EVENT_REGISTER_ELEMENT_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = Accommodation::class;
        });
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {
            $event->rules['accommodations'] = ['template' => '_recranet-booking/accommodations/_index.twig'];
            $event->rules['accommodations/<elementId:\d+>'] = 'elements/edit';
        });
    }
}
