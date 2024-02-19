.. _tsconfigPlugin:

====================
Plugin configuration
====================

This section covers settings which influence the news plugin.

Example: Remove the setting of flexforms
----------------------------------------

The following examples removes the field `recursive` from the plugin "Tag List".

.. code-block:: typoscript

   # Example:
   TCEFORM.tt_content.pi_flexform.news_taglist.sDEF {
      # As the dot is part of the fieldname, it needs to be escaped
      settings\.recursive.disabled = 1
   }



