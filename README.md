# Recranet Booking

Synchronizes various Recranet Booking sources to Craft CMS

## Features

- Synchronizes facilities, accommodations, locality categories, and accommodation categories from Recranet Booking to Craft CMS.
- Supports custom fields for facilities, accommodation categories and locality categories.
- Generates an accommodation sitemap.xml route
- Stores the organization ID and 'reservation' page in the Craft CMS settings for easy access.

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
```