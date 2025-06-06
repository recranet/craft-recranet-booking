<?php

namespace recranet\craftrecranetbooking;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Elements;
use craft\web\UrlManager;
use recranet\craftrecranetbooking\elements\Facility;
use recranet\craftrecranetbooking\models\Settings;
use yii\base\Event;

/**
 * Recranet Booking plugin
 *
 * @method static RecranetBooking getInstance()
 * @method Settings getSettings()
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
                // Define component configs here...
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
        $navItem['icon'] = '@recranet/craftrecranetbooking/icon.svg';
        $navItem['subnav'] = [
            'facilities' => [
                'url' => 'recranet-booking/facilities',
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

        // Register event handlers here ...
        // (see https://craftcms.com/docs/5.x/extend/events.html to get started)
        Event::on(Elements::class, Elements::EVENT_REGISTER_ELEMENT_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = Facility::class;
        });
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {
            $event->rules['facilities'] = ['template' => '_recranet-booking/facilities/_index.twig'];
            $event->rules['facilities/<elementId:\d+>'] = 'elements/edit';
        });
    }
}
