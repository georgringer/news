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
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;

/**
 * ViewHelper to render meta tags
 *
 * # Example: Basic Example: News title as og:title meta tag
 * # MetaTagManager from the core decide if "property" or "name" is used
 * <code>
 * <n:metaTag property="og:title" content="{newsItem.title}" />
 * </code>
 * <output>
 * <meta property="og:title" content="TYPO3 is awesome" />
 * </output>
 *
 * <code>
 * <n:metaTag property="keywords" content="{newsItem.keywords}" />
 * </code>
 * <output>
 * <meta name="keywords" content="news 1, news 2" />
 * </output>
 */
class MetaTagViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * Arguments initialization
     *
     */
    public function initializeArguments()
    {
        if (version_compare(TYPO3_version, '9.0.0') >= 0) {
            $this->registerArgument('property', 'string', 'Property of meta tag', true);
            $this->registerArgument('content', 'string', 'Content of meta tag');
        }else{
            $this->registerTagAttribute('property', 'string', 'Property of meta tag');
            $this->registerTagAttribute('name', 'string', 'Content of meta tag using the name attribute');
            $this->registerTagAttribute('content', 'string', 'Content of meta tag');
        }
        $this->registerArgument('useCurrentDomain', 'boolean', 'Use current domain', false, false);
    }

    /**
     * Renders a meta tag

     */
    public function render()
    {
        // Skip if current record is part of tt_content CType shortcut
        if(!empty($GLOBALS['TSFE']->recordRegister)
            && is_array($GLOBALS['TSFE']->recordRegister)
            && strpos(array_keys($GLOBALS['TSFE']->recordRegister)[0], 'tt_content:') !== false
            && !empty($GLOBALS['TSFE']->currentRecord)
            && strpos($GLOBALS['TSFE']->currentRecord, 'tx_news_domain_model_news:') !== false
        ) {
            return;
        }

        if (version_compare(TYPO3_version, '9.0.0') >= 0) {
            $metaTagManagerRegistry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);
            $manager = $metaTagManagerRegistry->getManagerForProperty($this->arguments['property']);

            if ($this->arguments['useCurrentDomain']) {
                $manager->addProperty($this->arguments['property'], GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
            } else if (isset($this->arguments['content']) && !empty($this->arguments['content'])) {
                $manager->addProperty($this->arguments['property'], $this->arguments['content']);
            }
        }else{
            if ($this->arguments['useCurrentDomain']) {
                $this->tag->addAttribute('content', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
            }
            if ($this->arguments['useCurrentDomain'] || (isset($this->arguments['content']) && !empty($this->arguments['content']))) {
                $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
                $pageRenderer->addMetaTag($this->tag->render());
            }
        }
    }
}
