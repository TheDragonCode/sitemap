# Release Notes for Laravel Sitemap

## 2.0.0

### Added
- Add Upgrade Guide.
- Laravel 5.4 supported.

### Fixed
- Data too long for column 'loc' [#7](https://github.com/andrey-helldar/sitemap/issues/7)
    

## 1.0.4
### Added
- Added parameter to the method of generation cards by model.
- Added migration to store references to the database.
- Added console command to clean the database of old records.

### Fixed
- Adjusting the maximum age parameter records to be added to the sitemap.
- When manually adding links, they are stored in a database table.


## 1.0.3
### Added
- Added option to save the file name.
- Added function to manually generate a site on the transmitted information.
- Add CHANGELOG.

### Changed
- Changed the location of some resources.

### Removed
- Remove default route.


## 1.0.2
### Changed
- Changed definition file lifetime from "days" on "minutes".


## 1.0.1
- First Initial.
