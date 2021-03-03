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
 * ViewHelper to render meta tags
 *
 * # Example: Basic Example: News title as og:title meta tag
 * <code>
 * <n:metaTag property="og:title" content="{newsItem.title}" />
 * </code>
 * <output>
 * <meta property="og:title" content="TYPO3 is awesome" />
 * </output>
 *
 * # Example: Force the attribute "name"
 * <code>
 * <n:metaTag name="keywords" content="{newsItem.keywords}" />
 * </code>
 * <output>
 * <meta name="keywords" content="news 1, news 2" />
 * </output>
 */
class MetaTagViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Arguments initialization
     */
    public function initializeArguments()
    {
        $this->registerArgument('property', 'string', 'Property of meta tag', false, '', false);
        $this->registerArgument('name', 'string', 'Content of meta tag using the name attribute', false, '', false);
        $this->registerArgument('content', 'string', 'Content of meta tag', true, null, false);
        $this->registerArgument('useCurrentDomain', 'boolean', 'Use current domain', false, false);
        $this->registerArgument('forceAbsoluteUrl', 'boolean', 'Force absolut domain', false, false);
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        // Skip if current record is part of tt_content CType shortcut
        if (!empty($GLOBALS['TSFE']->recordRegister)
            && is_array($GLOBALS['TSFE']->recordRegister)
            && strpos(array_keys($GLOBALS['TSFE']->recordRegister)[0], 'tt_content:') !== false
            && !empty($GLOBALS['TSFE']->currentRecord)
            && strpos($GLOBALS['TSFE']->currentRecord, 'tx_news_domain_model_news:') !== false
        ) {
            return;
        }

        $useCurrentDomain = $arguments['useCurrentDomain'];
        $forceAbsoluteUrl = $arguments['forceAbsoluteUrl'];
        $content = (string)$arguments['content'];

        // set current domain
        if ($useCurrentDomain) {
            $content = GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
        }

        // prepend current domain
        if ($forceAbsoluteUrl) {
            $parsedPath = parse_url($content);
            if (is_array($parsedPath) && !isset($parsedPath['host'])) {
                $content =
                    rtrim(GeneralUtility::getIndpEnv('TYPO3_SITE_URL'), '/')
                    . '/'
                    . ltrim($content, '/');
            }
        }

        if ($content !== '') {
            $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
            if ($arguments['property']) {
                $pageRenderer->setMetaTag('property', $arguments['property'], $content);
            } elseif ($arguments['name']) {
                $pageRenderer->setMetaTag('name', $arguments['name'], $content);
            }
        }
    }
}
