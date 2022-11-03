<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Service\Import;

/**
 * Import Service interface
 */
interface DataProviderServiceInterface
{
    public function getTotalRecordCount();

    public function getImportData();
}
