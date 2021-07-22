<?php

namespace GeorgRinger\News\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to render data in <head> section of website
 *
 * # Example: Basic example
 * <code>
 * <n:headerData>
 *        <link rel="alternate"
 *            type="application/rss+xml"
 *            title="RSS 2.0"
 *            href="{f:uri.page(pageType: settings.list.rss.channel.typeNum)}" />
 * </n:headerData>
 * </code>
 * <output>
 * Added to the header: <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="uri to this page and type 9818" />
 * </output>
 */
class HeaderDataViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return void
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addHeaderData($renderChildrenClosure());
    }
}
