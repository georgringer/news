<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\ViewHelpers;

use GeorgRinger\News\Seo\NewsTitleProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to render the page title
 *
 * # Example: Basic Example
 * # Description: Render the content of the VH as page title
 * <code>
 *    <n:titleTag>{newsItem.title}</n:titleTag>
 * </code>
 * <output>
 *    <title>TYPO3 is awesome</title>
 * </output>
 */
class TitleTagViewHelper extends AbstractViewHelper
{
    public function render(): void
    {
        $isInsertRecord = $this->renderingContext->getVariableProvider()->getByPath('settings.insertRecord');
        if ($isInsertRecord) {
            return;
        }

        $content = trim($this->renderChildren());
        if (!empty($content)) {
            GeneralUtility::makeInstance(NewsTitleProvider::class)->setTitle($content);
        }
    }
}
