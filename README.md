# Recranet Booking

Synchronizes various Recranet Booking sources to Craft CMS

## Requirements

This plugin requires Craft CMS 5.7.0 or later, and PHP 8.2 or later.

## Installation

1. You can install the Recranet Booking plugin via Composer, first add the repository to your `composer.json` file and add it as a dependency:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:recranet/craft-recranet-booking.git"
    }
  ],
  "require": {
    "recranet/craft-recranet-booking": "^1.0"
  }
}
```

2. After this you can run `composer require recranet/craft-recranet-booking` to install the plugin. Don't forget to install and enable the plugin in the Craft CMS control panel or CLI.

## Commands

You can run the following command to synchronize the bookings:

```bash
php craft _recranet-booking/facilities
php craft _recranet-booking/accommodations
php craft _recranet-booking/locality-category
php craft _recranet-booking/accommodation-category
```

## Features

- Synchronizes facilities, accommodations, locality categories, and accommodation categories from Recranet Booking to Craft CMS.
- Supports custom fields for facilities, accommodation categories and locality categories.
- Generates an accommodation sitemap.xml route
- Stores the organization ID and 'reservation' page in the Craft CMS settings for easy access.