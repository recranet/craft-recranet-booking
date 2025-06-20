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

## Commands

You can run the following command to synchronize the bookings:

```bash
php craft _recranet-booking/facilities
php craft _recranet-booking/accommodations
php craft _recranet-booking/locality-category
php craft _recranet-booking/accommodation-category
php craft _recranet-booking/package-specification-category
```

## Variables

You can run the use the following variables in your templates to access the data:

```twig
{{ craft.recranetBooking.organizationId }}
```