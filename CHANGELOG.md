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