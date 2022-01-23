.. include:: /Includes.rst.txt

.. _tsconfigPlugin:

====================
Plugin configuration
====================

This section covers settings which influence the news plugin.

switchableControllerAction
==========================

.. confval:: switchableControllerAction

   :type: array
   :Path: TCEFORM > tt_content > pi_flexform > news_pi1.sDEF
   :Default:
      - News->list
      - News->detail
      - News->dateMenu
      - News->searchForm
      - News->searchResult
      - Category->list
      - Tag->list

   To remove a specific action from the News Plugin selectbox, use
   this snippet.

Example: Remove the action Tag->list
------------------------------------

.. code-block:: typoscript

   # Example:
   TCEFORM.tt_content.pi_flexform.news_pi1.sDEF {
      switchableControllerActions.removeItems = Tag->list
   }



