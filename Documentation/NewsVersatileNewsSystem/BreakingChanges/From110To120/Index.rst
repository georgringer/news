.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt
.. include:: Images.txt


From 1.1.0 to 1.2.0
^^^^^^^^^^^^^^^^^^^

- The LinkViewHelper changed a bit. The arguments  **renderTypeClass,
  class and linkOnly have been removed** ! Instead you can use the
  argument configuration which expects an array and can be filled with
  all options of typoLink. If you need the linkOnly (e.g. for links in
  RSS feed), use for example:  *configuration="{returnLast:'url'}"* .

|img-2| 26


