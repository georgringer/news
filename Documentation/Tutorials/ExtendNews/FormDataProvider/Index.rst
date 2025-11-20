.. _formDataProvider:

================
FormDataProvider
================

`FormDataProvider <https://docs.typo3.org/permalink/t3coreapi:formengine-datacompiling>`_
can be used to offer form-handling beyond the options provided by `displayCond` and `eval` in the TCA.
Among other things, a FormDataProvider allows dynamic TCA changes while records are loaded in the TYPO3 backend.

Example
-------

This example explains how to make at least one image mandatory if the record is flagged as top news.

1) Update TCA
~~~~~~~~~~~~~

Create the file `Configuration/TCA/Overrides/tx_news_domain_model_news.php` in your custom extension.
See `here <ext-based-on-news>`_ how to create that extension.

.. code-block:: php

    <?php

    defined('TYPO3') or die();

    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['istopnews']['onChange'] = 'reload';

With `'onChange' => 'reload'` you trigger a reload of the backend interface when the "Top news" toggle is clicked.

2) Register FormDataProvider class
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Update file `ext_localconf.php` in your extension:

.. code-block:: php

    <?php

    defined('TYPO3') or die();

    use Vendor\CustomExtension\Backend\Form\FormDataProvider\ImageMinItems;
    use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew;

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][ImageMinItems::class] = [
        'depends' => [
            DatabaseRowInitializeNew::class,
        ],
    ];

3) Add your FormDataProvider class
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace Vendor\CustomExtension\Backend\Form\FormDataProvider;

    use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

    final class ImageMinItems implements FormDataProviderInterface
    {
        public function addData(array $result): array
        {
            if ($result['tableName'] == 'tx_news_domain_model_news' && $result['databaseRow']['istopnews'] == 1) {
                $result['processedTca']['columns']['fal_media']['config']['minitems'] = 1;
            }
            return $result;
        }
    }

This class modifies the TCA when the current record is `tx_news_domain_model_news` and `istopnews` is set.
