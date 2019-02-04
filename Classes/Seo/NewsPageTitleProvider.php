<?php

namespace GeorgRinger\News\Seo;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

/**
 * This class will take care of the seo title that can be set in the backend
 * $titleProvider = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\GeorgRinger\News\Seo\NewsPageTitleProvider::class);
 * $titleProvider->setTitle('Title');
 */
class NewsPageTitleProvider extends AbstractPageTitleProvider
{
    public function setTitle($title)
    {
        $this->title = (string)$title;
    }
}
