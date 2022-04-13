[![Latest Stable Version](https://poser.pugx.org/georgringer/news/v/stable)](https://extensions.typo3.org/extension/news/)
[![TYPO3 11](https://img.shields.io/badge/TYPO3-11-orange.svg)](https://get.typo3.org/version/11)
[![TYPO3 10](https://img.shields.io/badge/TYPO3-10-orange.svg)](https://get.typo3.org/version/10)
[![Total Downloads](https://poser.pugx.org/georgringer/news/d/total)](https://packagist.org/packages/georgringer/news)
[![Monthly Downloads](https://poser.pugx.org/georgringer/news/d/monthly)](https://packagist.org/packages/georgringer/news)
![Build](https://github.com/georgringer/news/actions/workflows/ci.yml/badge.svg)
[![StyleCI](https://styleci.io/repos/11733164/shield?branch=master)](https://styleci.io/repos/11733164/)
[![Crowdin](https://badges.crowdin.net/typo3-extension-news/localized.svg)](https://crowdin.com/project/typo3-extension-news)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/GeorgRinger/25)

# TYPO3 Extension `news`

This extension implements a versatile news system based on Extbase & Fluid and
uses the latest technologies provided by TYPO3 CMS.

It includes these features:

* Support for editors and authors by

   * well-structured plugins with good preview functionality
   * a backend module with filter & search
   * frontend template variant based on Twitter Bootstrap v5.

* Use of as many elements from the system core as possible, e.g. FAL and system
  categories.
* Built-in support for content elements.
* Support for Open Graph and social platforms in the default template.
* Complete and comprehensive documentation.

> It is a highly customizable framework for chronological organized content -
> much more than just a news list!
> – Quote from a user

|                  | URL                                                   |
|------------------|-------------------------------------------------------|
| **Repository:**  | https://github.com/georgringer/news                   |
| **Read online:** | https://docs.typo3.org/p/georgringer/news/main/en-us/ |
| **TER:**         | https://extensions.typo3.org/extension/news/          |

[Sponsor](https://docs.typo3.org/p/georgringer/news/main/en-us/Introduction/Support/Index.html#sponsoring)
us to get things done for your business - in time and quality.

## 4 Administration corner

### 4.1 Versions and support

| News     | TYPO3     | PHP       | Support / Development                |
|----------|-----------|-----------|--------------------------------------|
| dev-main | 10 - 11   | 7.4 - 8.1 | unstable development branch          |
| 9        | 10 - 11   | 7.4 - 8.1 | features, bugfixes, security updates |
| 8        | 9.5 - 10  | 7.2 - 7.4 | none                                 |
| 7.x      | 8.7 - 9.x | 7.0 - 7.2 | none                                 |
| 6.x      | 7.6 - 8.7 | 5.6 - 7.2 | none                                 |
| 5.x      | 7.6 - 8.7 | 5.6 - 7.2 | none                                 |
| 4.x      | 7.6       | 5.5 - 5.6 | none                                 |
| 3.x      | 6.2       | 5.5 - 5.6 | security updates                     |

### 4.2 Changelog

Please look into the [official extension documentation in changelog chapter][4].

### 4.3 Release Management

News uses [**semantic versioning**][5], which means, that
* **bugfix updates** (e.g. 1.0.0 => 1.0.1) just includes small bugfixes or security relevant stuff without breaking changes,
* **minor updates** (e.g. 1.0.0 => 1.1.0) includes new features and smaller tasks without breaking changes,
* and **major updates** (e.g. 1.0.0 => 2.0.0) breaking changes wich can be refactorings, features or bugfixes.

### 4.4 Contribution

**Pull Requests** are gladly welcome! Nevertheless please don't forget to add an issue and connect it to your pull requests. This
is very helpful to understand what kind of issue the **PR** is going to solve.

Bugfixes: Please describe what kind of bug your fix solve and give us feedback how to reproduce the issue. We're going
to accept only bugfixes if we can reproduce the issue.

Features: Not every feature is relevant for the bulk of `news` users. In addition: We don't want to make ``news``
even more complicated in usability for an edge case feature. It helps to have a discussion about a new feature before you open a pull request.


[4]: https://docs.typo3.org/p/georgringer/news/master/en-us/Misc/Changelog/Index.html
[5]: https://semver.org/
