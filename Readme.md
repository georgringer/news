# TYPO3 Extension ``news`` [![Build Status](https://travis-ci.org/georgringer/news.png)](https://travis-ci.org/georgringer/news)

Versatile news extension, based on extbase & fluid. It is editor friendly, default integration of social sharing and many other features.

## Requirements

- TYPO3 7.6 - 8.x (use branch ``6x`` for TYPO3 6.2 LTS)

## Documentation

The full documentation can be found on (docs.typo3.org)[1]. However the usage is fairly straight forward!

### Installation

1) Install the extension by using the extension manager or by using composer ``composer require georgringer/news``.
2) Include the static TypoScript of the extension
3) Optional: If you are templates are based on Twitter Bootstrap, add the TWB styles as well to get optimized templates

### Usage

1) Create some news records on a sysfolder.
2) Create a plugin on a page and select at least the sysfolder as startingpoint.



[1]: https://docs.typo3.org/typo3cms/extensions/news/