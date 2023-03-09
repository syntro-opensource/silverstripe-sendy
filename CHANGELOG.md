# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

<a name="unreleased"></a>
## [Unreleased]


<a name="1.0.5"></a>
## [1.0.5] - 2023-03-09
### 🍰 Added
- Newsletters can be created with different styles ([#8](https://github.com/syntro-opensource/silverstripe-sendy/issues/8))


<a name="1.0.4"></a>
## [1.0.4] - 2022-07-21
### 🍰 Added
- standardized testsuites ([#4](https://github.com/syntro-opensource/silverstripe-sendy/issues/4))

### 🐞 Fixed
- Campaigns are correctly sorted

### 🔧 Changed
- allow composer plugins


<a name="1.0.3"></a>
## [1.0.3] - 2022-02-18
### 🐞 Fixed
- Backend requirements (css, js) are no longer sent to Sendy

### 🔧 Changed
- preview & upload fields are no longer visible during campaign creation (closes [#3](https://github.com/syntro-opensource/silverstripe-sendy/issues/3))

### 🗑 Removed
- drop php 7.3 in tests


<a name="1.0.2"></a>
## [1.0.2] - 2022-02-10
### 🐞 Fixed
- added `canArchive` to `SendyCampaign`
- changelog uses correct links


<a name="1.0.1"></a>
## [1.0.1] - 2022-02-09
### 🐞 Fixed
- text alignment works without classes


<a name="1.0.0"></a>
## 1.0.0 - 2022-02-09
### 🍰 Added
- validation of campaigns
- german translation
- better templating docs
- initial commit

### 🐞 Fixed
- doc states the fact that `Title` != `Subject`


[Unreleased]: https://github.com/syntro-opensource/silverstripe-sendy/compare/1.0.5...HEAD
[1.0.5]: https://github.com/syntro-opensource/silverstripe-sendy/compare/1.0.4...1.0.5
[1.0.4]: https://github.com/syntro-opensource/silverstripe-sendy/compare/1.0.3...1.0.4
[1.0.3]: https://github.com/syntro-opensource/silverstripe-sendy/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/syntro-opensource/silverstripe-sendy/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/syntro-opensource/silverstripe-sendy/compare/1.0.0...1.0.1
