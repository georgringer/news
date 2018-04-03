# TYPO3 Extension ``news``

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/GeorgRinger/19.99)
[![Build Status](https://travis-ci.org/georgringer/news.png)](https://travis-ci.org/georgringer/news)
[![StyleCI](https://styleci.io/repos/11733164/shield?branch=master)](https://styleci.io/repos/11733164/)
[![Latest Stable Version](https://poser.pugx.org/georgringer/news/v/stable)](https://packagist.org/packages/georgringer/news)
[![Monthly Downloads](https://poser.pugx.org/georgringer/news/d/monthly)](https://packagist.org/packages/georgringer/news)
[![License](https://poser.pugx.org/georgringer/news/license)](https://packagist.org/packages/georgringer/news)

> It is a highly customizable framework for chronological organized content - much more than just a news list! (Quote by an user)

## 1. Features

- Based on extbase & fluid, implementing best practices from TYPO3 CMS
- Supporting editors & authors by providing
 - well structured plugins with good preview functionality
 - backend module with filter & search
- Frontend template variant based on Twitter Bootstrap (v3)
- (Well documented)[1]

## 2. Usage


### 1) Installation

#### Installation using Composer

The recommended way to install the extension is by using [Composer][2]. In your Composer based TYPO3 project root, just do `composer require georgringer/news`. 

#### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the extension manager module.

### 2) Minimal setup

1) Include the static TypoScript of the extension. **Optional:** If your templates are based on Twitter Bootstrap, add the TWB styles as well to get optimized templates.
2) Create some news records on a sysfolder.
3) Create a plugin on a page and select at least the sysfolder as startingpoint.

## 3. Help supporting further development

**Why?** The news extension is a powerful tool with a lot of features, always trying to thrive on the latest possibilities of the TYPO3 core. This implies a lot of work bringing this to the TYPO3 community.

**How?** There are multiple ways to support the further development

- **Patreon**: Support me on [patreon.com](https://www.patreon.com/georgringer) and get access to additional extensions and snippets as reward.
- **PayPal**: Support me by a donation on [paypal.com](https://www.paypal.me/GeorgRinger/25). It is just one click away.
- **Amazon Wishlist**: Satisfy a wish of my [Amazon wishlist](https://www.amazon.de/hz/wishlist/ls/8F573K08TSDG).


## 4. Administration corner

### 4.1. Versions and support

| News        | TYPO3      | PHP       | Support/Development                     |
| ----------- | ---------- | ----------|---------------------------------------- |
| 7.x         | 8.7 - 9.x  | 7.0 - 7.2 | Features, Bugfixes, Security Updates    |
| 6.x         | 7.6 - 8.7  | 5.6 - 7.2 | Features, Bugfixes, Security Updates    |
| 5.x         | 7.6 - 8.7  | 5.6 - 7.2 | none                                    |
| 4.x         | 7.6        | 5.5 - 5.6 | none                                    |
| 3.x         | 6.2        | 5.5 - 5.6 | Security Updates                        |

### 4.2. Changelog

Please look into the [official extension documentation in changelog chapter](https://docs.typo3.org/typo3cms/drafts/github/georgringer/news/Misc/Changelog/Index.html)

### 4.3. Release Management

News uses **semantic versioning** which basically means for you, that
- **bugfix updates** (e.g. 1.0.0 => 1.0.1) just includes small bugfixes or security relevant stuff without breaking changes.
- **minor updates** (e.g. 1.0.0 => 1.1.0) includes new features and smaller tasks without breaking changes.
- **major updates** (e.g. 1.0.0 => 2.0.0) breaking changes wich can be refactorings, features or bugfixes.

### 4.4. Contribution

**Pull requests** are welcome in general! Nevertheless please don't forget to add an issue and connect it to your pull requests. This
is very helpful to understand what kind of issue the **PR** is going to solve.

- Bugfixes: Please describe what kind of bug your fix solve and give us feedback how to reproduce the issue. We're going
to accept only bugfixes if I can reproduce the issue.
- Features: Not every feature is relevant for the bulk of ``news`` users. In addition: We don't want to make ``news``
even more complicated in usability for an edge case feature. Please discuss a new feature before.




[1]: https://docs.typo3.org/typo3cms/extensions/news/
[2]: https://getcomposer.org/
