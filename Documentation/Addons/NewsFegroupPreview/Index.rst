    .. _newsFegroupPreview:

========================
EXT:news_fegroup_preview
========================

This extensions makes it possible to render news records with a fe_group restriction even though no user is logged in.

By using a custom ViewHelper it is possible to check in the view if the news record should be shown fully or e.g. just a teaser and a link to the login form.

Pricing
-------

- € 100.00 (ex. VAT) for 1 site

**Included**: Bugfix & feature releases, composer support

Contact: Please write an email to extension@ringer.it with the version you need and your invoice address + VAT ID.
Additionally please add your GitHub username to get access to the repository to be able to download latest updates.

Usage
-----

Configuration
^^^^^^^^^^^^^
Open a news list plugin in the backend and select those frontend groups which should be ignored in the rendering. This means that news records with those groups assigned will be still rendered!

.. include:: /Images/Addons/NewsFegroupPreview/plugin.rst.txt

Templating
^^^^^^^^^^

.. code-block:: html

   <preview:security.defaultVisible groups="{newsItem.feGroup}">
       <f:then>
           <n:link newsItem="{newsItem}" settings="{settings}" title="{newsItem.title}">
               {newsItem.title}
           </n:link>
       </f:then>
       <f:else>
           <!--
               This news record is only available because of defined preview,
               show a preview or similar -->
           {newsItem.title}
           <f:link.page pageUid="123" additionalParams="{redirect_url:'{n:link(newsItem:newsItem,settings:settings,uriOnly:1)}'}">
               Login to read all
           </f:link.page>
       </f:else>
   </preview:security.defaultVisible>

