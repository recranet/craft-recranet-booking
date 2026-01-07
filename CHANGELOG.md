# Changelog

All notable changes to this project will be documented in this file.

## [5.0.0] - 2025-06-05
### Added
- Initial plugin release

## [5.0.1] - 2025-06-11
### Fixed
- Fixed an issue on first install where the plugin settings were not initialized correctly.

## [5.0.2] - 2025-06-14
### Added
- Added import of multi-language accommodation slugs.

## [5.0.3] - 2025-06-14
### Added
- Added accommodation select field-type.

## [5.0.4] - 2025-06-16
### Fixed
- Fixed an issue where organization ID was not being saved correctly in the plugin settings.

## [5.0.5] - 2025-06-16
### Added
- Added a new variable `craft.recranetBooking.organizationid` to access the organization ID in templates.

## [5.0.6] - 2025-06-16
### Fixed
- Fixed an issue where accommodations no longer present in the API response were not being deleted.

## [5.0.7] - 2025-06-19
### Added
- Added a new element type 'Package Specification Category' to the plugin.

## [5.0.8] - 2025-06-19
### Added
- Made 'accommodations' available in twig templates via the `craft.accommodations()` variable.

## [5.0.9] - 2025-06-19
### Hotfix
- Fixed a typo in sitemap template

## [5.1.0] - 2025-06-30
### Added
- Added new commands to delete all facilities, accommodations, locality categories, accommodation categories, and package specification categories.
### Fixed
- Fixed an issue with missing translated slugs from accommodations

## [5.1.1] - 2025-07-10
### Fixed
- Made translated slugs nullable if not available in the API response.

## [5.1.2] - 2025-07-16
### Fixed
- Fixed that unused elements were not being deleted. Unused elements are now deleted on every command run.

## [5.1.3] - 2025-07-16
### Added
- Add routing for Recranet Booking

## [5.1.4] - 2025-07-16
### Added
- Added translated title fields

## [5.1.5] - 2025-07-16
### Added
- Added import and delete all command
- Refactor commands

## [5.1.6] - 2025-08-04
### Fixed
- Fixed control panel navigation submenu behavior to keep menu expanded when navigating between sections

## [5.1.7] - 2025-10-20
### Fixed
- Fixed issue with importing the wrong title property
- Fixed issue when subnav was closed on selecting an item
- Remove unused import

## [5.2.0] - 2025-12-20
### Refactored
- Refactored plugin to support configuring multiple organizations
- Each organization can be linked to a Site

