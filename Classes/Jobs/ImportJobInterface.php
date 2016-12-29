<?php

namespace GeorgRinger\News\Jobs;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Import job interface
 */
interface ImportJobInterface
{
    public function getNumberOfRecordsPerRun();

    public function getInfo();

    public function isEnabled();

    public function run($offset);
}
