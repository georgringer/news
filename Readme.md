# TYPO3 Extension ``news`` 

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/GeorgRinger/19.99)
[![Build Status](https://travis-ci.org/georgringer/news.png)](https://travis-ci.org/georgringer/news)
[![StyleCI](https://styleci.io/repos/11733164/shield?branch=master)](https://styleci.io/repos/11733164/)
[![Latest Stable Version](https://poser.pugx.org/georgringer/news/v/stable)](https://packagist.org/packages/georgringer/news)
[![Monthly Downloads](https://poser.pugx.org/georgringer/news/d/monthly)](https://packagist.org/packages/georgringer/news)
[![License](https://poser.pugx.org/georgringer/news/license)](https://packagist.org/packages/georgringer/news)

> It is a highly customizable framework for chronological organized content - much more than just a news list! (Quote by an user)

## Features

- Based on extbase & fluid, implementing best practices from TYPO3 CMS
- Supporting editors & authors by providing
 - well structured plugins with good preview functionality
 - backend module with filter & search
- Frontend template variant based on Twitter Bootstrap (v3) 
- (Well documented)[1]

## Usage


### 1) Installation

#### Installation using Composer

The recommended way to install the extension is by using (Composer)[2]. In your Composer based TYPO3 project root, just do `composer require georgringer/news`. 

#### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the extension manager module.

### 2) Minimal setup

1) Include the static TypoScript of the extension. **Optional:** If you are templates are based on Twitter Bootstrap, add the TWB styles as well to get optimized templates.
2) Create some news records on a sysfolder.
3) Create a plugin on a page and select at least the sysfolder as startingpoint.



[1]: https://docs.typo3.org/typo3cms/extensions/news/
[2]: https://getcomposer.org/
