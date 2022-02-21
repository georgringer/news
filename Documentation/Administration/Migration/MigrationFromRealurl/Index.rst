.. include:: /Includes.rst.txt

.. _migration_realurl_routing:

===========================================
Migration from realurl to news with routing
===========================================

This tutorial will describe to migrate realurl aliases to news path_segment.

Requirements
============

- Installed extension news
- DB table tx_realurl_uniqalias (EXT:realurl not required to be installed)

Migration
=========

Migration of aliases
--------------------

If a lot of similar titles are used it might be a good a idea to migrate the unique aliases from realurl to news path_segment to ensure that the same alias are used.

Use Installtool Upgrade Wizard, where a wizard only appears, if missing slugs found between realurl and news.
Requires database table "tx_realurl_uniqalias" from EXT:realurl, but EXT:realurl requires not to be installed.

This wizard migrates only matching realurl alias for news entries, where path_segment is empty, respecting language and expire date from realurl.

Cause only empty news slugs will be generated within this migration, you may decide to empty all news slugs before.

The result of this migration can still left empty slugs fields for news entries.
Therefore you should generate these slugs afterwards using the news slug updater wizard.

Configure routing
-----------------

Read the following chapter on :ref:`How to rewrite URLs with news parameters
<how_to_rewrite_urls>`.
