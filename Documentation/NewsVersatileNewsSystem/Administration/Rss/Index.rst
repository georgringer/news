.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


RSS
^^^

No matter which way you use to create an RSS feed, the template for
the output is by default Resources/Private/Templates/News/List.xml.
The xml file type is achieved by setting settings.format = xml. No
magic at all.


Rss feeds by using a normal plugin
""""""""""""""""""""""""""""""""""

news supports RSS feeds which are handled exactly like a normal list
view plugin. To create a RSS feed all you need to do is:

#. Create a new page.

#. Add the news plugin and set the configuration

#. Define a new TypoScript template and use a code like below.  **Very
   important** : Use config.absRefPrefix = http://www.yourdomain.tld/ to
   produce absolute urls for links and images! ::

      page = PAGE
      page.10 < styles.content.get
   
      config {
              # deactivate Standard-Header
             disableAllHeaderCode = 1
             # no xhtml tags
             xhtml_cleaning = none
             admPanel = 0
             metaCharset = utf-8
             # define charset
             additionalHeaders = Content-Type:text/xml;charset=utf-8
             disablePrefixComment = 1
      }

   # set the format
   plugin.tx_news.settings.format = xml

If your output still contains HTML code, please check your TypoScript
(especially from css\_styled\_content) as this HTML is produced there!


Rss feeds by embedding the plugin with TypoScript
"""""""""""""""""""""""""""""""""""""""""""""""""

If you feel more comfortable by using plain TypoScript to embedd the
plugin, you can use this code ::

   lib.news < tt_content.list.20.news_pi1
   lib.news {
     switchableControllerActions {
       News {
         1 = list
       }
     }
     settings < plugin.tx_news.settings
     settings {
       //categories = 49
       limit = 30
       detailPid = 31
       startingpoint = 13
       format = xml
     }
   }

Further information in the wiki at `http://forge.typo3.org/projects
/extension-news/wiki/For\_Integrators <http://forge.typo3.org/projects
/extension-news/wiki/For_Integrators>`_

