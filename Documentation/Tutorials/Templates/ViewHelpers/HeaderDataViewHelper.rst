HeaderDataViewHelper
-------------------------

ViewHelper to render data in <head> section of website

**Type:** Basic


Examples
^^^^^^^^^^^^^

Basic example
""""""""""""""""""



Code: ::

    <n:headerData>
          <link rel="alternate"
             type="application/rss+xml"
             title="RSS 2.0"
             href="{f:uri.page(pageType: settings.list.rss.channel.typeNum)}" />
    </n:headerData>


Output: ::

    Added to the header: <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="uri to this page and type 9818" />

