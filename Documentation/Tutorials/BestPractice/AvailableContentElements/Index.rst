.. include:: /Includes.rst.txt

.. _limitCE:

======================
Limit content elements
======================

If you want to limit the available content element types for news records, you have two options to configure that with Page TSConfig:

1. ``TCEFORM`` ::

      TCEFORM.tt_content.CType.removeItems = html,bullets,div,menu_subpages

2. Backend Layout settings: ::

      mod.web_layout.BackendLayouts.1.config.backend_layout.rows.1.columns.1.allowed = textmedia,textpic
      TCAdefaults.tt_content.CType = textmedia

With the first line you change the allowed CTypes for the backend layout that applies to your news sysfolder.
The second line sets the default CType to prevent an error with e.g. ``INVALID VALUE ("text")``

.. Hint::

   Regardless which option you choose, you need to wrap the TSConfig code with a TypoScript condition to limit the restriction to the sysfolder(s) where your news records reside, e.g with ``[123 in tree.rootLineIds]``

.. Hint::

   Option 2 has the advantage, that if you want to allow only a very small number of content element types, you don't need to remove explicitly every CType. It's rather a whitelist approach.
