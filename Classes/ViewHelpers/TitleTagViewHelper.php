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
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

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
    use CompileWithRenderStatic;

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): void {
        // Skip if current record is part of tt_content CType shortcut
        $tsfe = self::getTsfe();
        if (!empty($tsfe->recordRegister)
            && is_array($tsfe->recordRegister)
            && str_contains(array_keys($GLOBALS['TSFE']->recordRegister)[0], 'tt_content:')
            && !empty($tsfe->currentRecord)
            && str_contains($tsfe->currentRecord, 'tx_news_domain_model_news:')
        ) {
            return;
        }
        $content = trim($renderChildrenClosure());
        if (!empty($content)) {
            GeneralUtility::makeInstance(NewsTitleProvider::class)->setTitle($content);
        }
    }

    protected static function getTsfe(): TypoScriptFrontendController
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        return $request->getAttribute('frontend.controller', $GLOBALS['TSFE']);
    }
}
