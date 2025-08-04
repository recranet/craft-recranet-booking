# Recranet Booking

Synchronizes various Recranet Booking sources to Craft CMS

## Features

- Synchronizes facilities, accommodations, locality categories accommodation categories and package specification categories from Recranet Booking to Craft CMS.
- Supports custom fields for facilities, accommodation categories, locality categories and package specification categories.
- Generates an accommodation sitemap.xml route
- Stores the organization ID and 'reservation' page in the Craft CMS settings for easy access.
- Exposes accommodations in twig templates via the `craft.accommodations()` variable.

## Requirements

This plugin requires Craft CMS 5.7.0 or later, and PHP 8.2 or later.

## Installation

```bash 
composer require recranet/craft-recranet-booking && php craft plugin/install _recranet-booking
```

## Setup
Create a new file inside `config/` called `_recranet-booking.php`. Below values can be overridden in this file. 
```php
<?php

return [
    '*' => [
        'showCpNav' => true,
    ],
    'dev' => [
        'showCpNav' => true,
    ],
    'staging' => [
        'showCpNav' => false,
    ],
    'production' => [
        'showCpNav' => false,
    ],
];
```

## Commands

You can run the following command to synchronize the different sources:

```bash
ddev php craft _recranet-booking/import

ddev php craft _recranet-booking/import/facilities
ddev php craft _recranet-booking/import/accommodations
ddev php craft _recranet-booking/import/locality-category
ddev php craft _recranet-booking/import/accommodation-category
ddev php craft _recranet-booking/import/package-specification-category
```

You can run the following command to delete the different sources:

```bash
ddev php craft _recranet-booking/delete

ddev php craft _recranet-booking/delete/facilities
ddev php craft _recranet-booking/delete/accommodations
ddev php craft _recranet-booking/delete/locality-category
ddev php craft _recranet-booking/delete/accommodation-category
ddev php craft _recranet-booking/delete/package-specification-category
```

## Variables

You can run the use the following variables in your templates to access the data:

```twig
{{ craft.recranetBooking.organizationId }}
```