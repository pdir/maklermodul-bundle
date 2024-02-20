# Changelog

[//]: <> (
Types of changes
    Added for new Addeds.
    Changed for changes in existing functionality.
    Deprecated for soon-to-be removed Addeds.
    Removed for now removed Addeds.
    Fixed for any bug fixes.
    Security in case of vulnerabilities.
)

## [2.9.2](https://github.com/pdir/maklermodul-bundle/tree/2.9.2) – 2024-02-20

- [Fixed] fix energy pass value

## [2.9.1](https://github.com/pdir/maklermodul-bundle/tree/2.9.1) – 2023-08-03

- [Fixed] back button error
- [Fixed] Fix dates in backend estate list view

## [2.9.0](https://github.com/pdir/maklermodul-bundle/tree/2.9.0) – 2023-07-21

- [Added] Add english and russian translations
- [Fixed] Fix field 'ausstattung.stellplatzart' in makler_details_extended template

## [2.8.7](https://github.com/pdir/maklermodul-bundle/tree/2.8.7) – 2023-03-23

- [Fixed] image path for sitemap generation

## [2.8.6](https://github.com/pdir/maklermodul-bundle/tree/2.8.6) – 2023-03-08

- [Fixed] Fix exception for list view with no objects
- [Fixed] Fix immoscout energy pass type

## [2.8.5](https://github.com/pdir/maklermodul-bundle/tree/2.8.5) – 2023-03-06

- [Fixed] Fix immoscout import

## [2.8.4](https://github.com/pdir/maklermodul-bundle/tree/2.8.4) – 2023-01-24

- [Added] filter fallback in list view

## [2.8.3](https://github.com/pdir/maklermodul-bundle/tree/2.8.3) – 2022-12-22

- [Fixed] Fix filter translation ([#34](https://github.com/pdir/maklermodul-bundle/issues/34) thanks to [agonyz](https://github.com/agonyz))

## [2.8.2](https://github.com/pdir/maklermodul-bundle/tree/2.8.2) – 2022-11-10

- [Fixed] SQL Exceptions for PHP 8.1
- [Fixed] Exception in PHP 8.1
- [Fixed] Database schema update [#30](https://github.com/pdir/maklermodul-bundle/issues/30)

## [2.8.1](https://github.com/pdir/maklermodul-bundle/tree/2.8.1) – 2022-09-22

- [Fixed] fix pagination error
- [Fixed] optimize image loading in list view [#25](https://github.com/pdir/maklermodul-bundle/issues/25)
- [Fixed] fix button filter [#15](https://github.com/pdir/maklermodul-bundle/issues/15)
- [Fixes] Class 'Contao\ArrayUtil' not found in Contao 4.9 [#29](https://github.com/pdir/maklermodul-bundle/issues/29)

## [2.8.0](https://github.com/pdir/maklermodul-bundle/tree/2.8.0) – 2022-09-16

- [Added] add php 8 support
- [Changed] remove patchwork/utf8
- [Fixed] remove the disabled attribute from buttons if reset filter
- [Fixed] fix filter values
- [Fixed] fix image sizes for list and detail view
- [Added] add image size for header image
- [Added] add image size for attachment images

## [2.7.9](https://github.com/pdir/maklermodul-bundle/tree/2.7.9) – 2021-09-15

- [Fixed] fix import for internal attachments

## [2.7.8](https://github.com/pdir/maklermodul-bundle/tree/2.7.8) – 2021-09-13

- [Fixed] fix import with database strict mode

## [2.7.7](https://github.com/pdir/maklermodul-bundle/tree/2.7.7) – 2021-05-20

- [Fixed] fix scss error in maklermodul_backend.scss

## [2.7.6](https://github.com/pdir/maklermodul-bundle/tree/2.7.6) – 2021-04-15

- [Fixed] add detail page exception in list view

## [2.7.5](https://github.com/pdir/maklermodul-bundle/tree/2.7.5) – 2021-03-31

- [Fixed] fix translations; fix editor in backend

## [2.7.4](https://github.com/pdir/maklermodul-bundle/tree/2.7.4) – 2021-03-16

- [Fixed] fix lastUpdate feature in demo

## [2.7.3](https://github.com/pdir/maklermodul-bundle/tree/2.7.3) – 2021-03-16

- [Fixed] fix lastUpdate feature in demo

## [2.7.2](https://github.com/pdir/maklermodul-bundle/tree/2.7.2) – 2021-03-09

- [Fixed] fix translation; fix demo import

## [2.7.1](https://github.com/pdir/maklermodul-bundle/tree/2.7.1) – 2021-01-26

- [Changed] add logo svg

## [2.7.0](https://github.com/pdir/maklermodul-bundle/tree/2.7.0) – 2021-01-25

- [Fixed] Immonet Import
- [Fixed] bugfix for filters
- [Fixed] jquery conflict if contao advanced classes is installed

## [2.6.5](https://github.com/pdir/maklermodul-bundle/tree/2.6.5) – 2020-10-10

- [Fixed] fix estate css classes
- [Fixed] sorting
- [Fixed] attach-group-filter
- [Fixed] placeholder in header image
- [Fixed] Attachment rendering: Fix filtering of given groups
- [Fixed] add valid character config for alias generation

## [2.5.3](https://github.com/pdir/maklermodul-bundle/tree/2.5.3) – 2020-06-17

- [Fixed] fix option bug in safari on ios

## [2.5.2](https://github.com/pdir/maklermodul-bundle/tree/2.5.2) – 2020-06-17

- [Fixed] fix cache problem; fix alias

## [2.5.1](https://github.com/pdir/maklermodul-bundle/tree/2.5.1) – 2020-05-14

- [Fixed] change badges
- [Fixed] remove PHP Warning: Illegal string offset 'error'

## [2.5.0](https://github.com/pdir/maklermodul-bundle/tree/2.5.0) – 2020-05-12

- [Fixed] reset filter for mate theme
- [Fixed] empty energy class
- [Added] add alias feature
- [Fixed] sorting for pagination
- [Fixed] filter selects
- [Fixed] pagination
- [Fixed] replace pipe in image url
- [Changed] add openimmo feedback form

## [2.4.0](https://github.com/pdir/maklermodul-bundle/tree/2.4.0) – 2019-11-14

- [Added] add condition type
- [Fixed] update filter select template
- [Fixed] remove second parameter in getTemplateGroup function
- [Fixed] update css for google maps
- [Fixed] fix headline in header image and update css for google maps

## [2.3.0](https://github.com/pdir/maklermodul-bundle/tree/2.3.0) – 2019-10-02

- [Added] attachments with format application/pdf
- [Fixed] pagination

## [2.2.0](https://github.com/pdir/maklermodul-bundle/tree/2.2.0) – 2019-08-16

- [Added] search for string in conditions
- [Added] add condition to filter objects by searching a string

## [2.1.5](https://github.com/pdir/maklermodul-bundle/tree/2.1.5) – 2019-08-15

- [Fixed] index command via console; fix url suffix for urls ending with '/'
- [Fixed] load data.filter.ini if refresh index via console command
- [Fixed] detail view alias if url suffix is '/'

## [2.1.4](https://github.com/pdir/maklermodul-bundle/tree/2.1.4) – 2019-08-09

- [Fixed] add backend scss for contao 4.4

## [2.1.3](https://github.com/pdir/maklermodul-bundle/tree/2.1.3) – 2019-08-08

- [Fixed] optimize filter
- [Changed] update descriptions
- [Fixed] hide licence infos if sync bundle is installed
- [Fixed] fix placeholder image

## [2.1.2](https://github.com/pdir/maklermodul-bundle/tree/2.1.2) – 2019-07-24

- [Fixed] hide reset filter if no filter is set in module
- [Fixed] show external images in list and detail view
- [Fixed] filters

## [2.1.1](https://github.com/pdir/maklermodul-bundle/tree/2.1.1) – 2019-07-17

- [Fixed] sitemap
- [Fixed] google maps if maps module is not loaded

## [2.1.0](https://github.com/pdir/maklermodul-bundle/tree/2.1.0) – 2019-03-26

- [Added] add issue templates
- [Added] add selection of header image source
- [Fixed] doc links
- [Fixed] static filter
- [Added] onOffice import

## [2.0.2](https://github.com/pdir/maklermodul-bundle/tree/2.0.2) – 2019-02-22

- [Fixed] SCSS refactoring; update price

## [2.0.1](https://github.com/pdir/maklermodul-bundle/tree/2.0.1) – 2019-01-22

- [Fixed] suffix

## [2.0.0](https://github.com/pdir/maklermodul-bundle/tree/2.0.0) – 2019-01-22

- [Added] add version for contao 4.5 and 4.6
- [Fixed] data filter; update doc

## [1.0.0](https://github.com/pdir/maklermodul-bundle/tree/1.0.0) – 2018-03-21

- [Added] first stable release
