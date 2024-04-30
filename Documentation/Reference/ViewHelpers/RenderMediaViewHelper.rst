
.. _viewHelperRenderMedia:

RenderMediaViewHelper
---------------------

ViewHelper to render media from any content

**Type:** Basic


General properties
^^^^^^^^^^^^^^^^^^

.. t3-field-list-table::
 :header-rows: 1

 - :Name: Name:
   :Type: Type:
   :Description: Description:
   :Default value: Default value:

 - :Name:
         news (required)
   :Type:
         Tx\_News\_Domain\_Model\_News
   :Description:
         The news post
   :Default value:

 - :Name:
         imgClass
   :Type:
         string
   :Description:
         Add css class to images.
   :Default value:

 - :Name:
         videoClass
   :Type:
         string
   :Description:
         Wrap videos in a div with this css class.
   :Default value:


 - :Name:
         audioClass
   :Type:
         string
   :Description:
         Wrap audio files in a div with this css class
   :Default value:


 - :Name:
         fileIndex
   :Type:
         integer
   :Description:
         index of image to start with
   :Default value:
         0

 - :Name:
         cropVariant
   :Type:
         string
   :Description:
         Select a cropping variant, in case multiple croppings have been specified or stored in FileReference
   :Default value:
         default



Examples
^^^^^^^^

Basic usage
"""""""""""

Use `[media]` in the RTE fields of a news. In Fluid tempate wrap rendering of text fields with the VieHelper.


Backend: ::

   Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy
   eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
   sed diam voluptua.

   [media]

   At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd
   gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

   [media]

   Lorem    ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy
   eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam
   voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita
   kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

Code: ::

   <n:renderMedia news="{newsItem}" imgClass="img-responsive" videoClass="video-wrapper" audioClass="audio-wrapper">
      <div class="news-text-wrap" itemprop="articleBody">
         <f:format.html>{newsItem.bodytext}</f:format.html>
      </div>
   </n:renderMedia>

Output: ::

   Media tags in RTE of the text are replaced with images.

.. tip::

   Move news detail template to your site-package and remove default rendering
   of images to avoid duplicates.

.. tip::

   Use `fileIndex` attribute to not start with the first image.

.. tip::

   Use "Show in views" setting for the images to control visibility in detail
   view.

.. tip::

   Change sorting of images in backend to change the order of images in
   frontend.
