.. _newsSeo:

============
EXT:news_seo
============

This extension improves the SEO features of EXT:news by providing the following fields:

- Index this article: `index` vs. `noindex`
- Follow this article: `follow` vs. `nofollow`
- Image Preview Size: Options standard, large, none

Even if no individual robot instructions are necessary, this extension solves the
problem to set the detail page to `noindex` to avoid being listed in the sitemap but *now*
having a `index` instruction when rendering an article record.

Details can be found at https://github.com/georgringer/news_seo

.. include:: /Images/Addons/NewsSeo/example.rst.txt

Usage
-----

- Use `composer req georgringer/news-seo`
- download it within the Extension Manager
- or download it from `https://extensions.typo3.org/extension/news_seo`

