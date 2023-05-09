# Changelog
All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.4.4] - 2023-05-09
- Added: allow passing listener through pdf generator context
- Fixed: warning

## [0.4.3] - 2023-05-05
- Changed: allow psr/log 2 and 3

## [0.4.2] - 2022-11-21
- Fixed: warnings under php 8

## [0.4.1] - 2022-07-27
- Fixed: page margin right config option name
- Fixed: font support from yaml configuration

## [0.4.0] - 2022-07-26
- Added: filePath option to configuration for pdf file output type ([#4])
- Added: margins and font options to yaml configuration ([#4])
- Added: PdfGeneratorContext override config property ([#4])
- Changed: minimum contao version is now 4.9 ([#4])
- Changed: minimum pdf creator version is now 0.4 ([#4])
- Changed: removed utils bundle dependency ([#4])
- Changed: removed utils bundle dependency ([#4])
- Fixed: symfony 5 related issues ([#4])
- Fixed: missing php dependency ([#4])

## [0.3.2] - 2022-03-10
- Changed: updated configuration documentation
- Fixed: used print encore entries instead of own encore entries in syndication type

## [0.3.1] - 2021-06-30
- fixed PdfGenerator::getConfiguration() not always returned null if config not found
- fixed PdfCreatorSyndicationType::export() not throws exception if export configuration not found

## [0.3.0] - 2021-05-25
- Add option to configure pdf creator from yaml ([#3])
- add base_url and credentials option (only yaml) ([#3])
- add PdfCreatorModel to the BeforeCreateLibraryInstanceEvent and BeforeOutputPdfCallbackEvent ([#3])
- fixed Dompdf log in prod mode ([#3])
- adjustments due changes in pdf creator library 0.3.2

## [0.2.2] - 2021-04-20
- increased pdf creator library dependency to 0.3
- added support for Dompdf (translations and custom debug implementation)
- added support for feature support check to hide functionality not provided by a pdf creator class

## [0.2.1] - 2021-04-06
- fixed missing syndication type translation

## [0.2.0] - 2021-03-09
- syndication type now use twig
- syndication type now supports encore bundle

## [0.1.1] - 2021-03-05
- updated the syndication type

## [0.1.0] - 2021-02-26
Initial release

[#4]: https://github.com/heimrichhannot/contao-pdf-creator-bundle/pull/4
[#3]: https://github.com/heimrichhannot/contao-pdf-creator-bundle/pull/3
