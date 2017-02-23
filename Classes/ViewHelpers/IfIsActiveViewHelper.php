<?php

namespace GeorgRinger\News\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;

/**
 * ViewHelper to check if the current news item is rendered as single view on the same page
 *
 * # Example: Basic example
 * <code>
 * {n:ifIsActive(newsItem: newsItem, then: 'active', else: '')}
 * </code>
 * <output>
 * Renders the string "active" if the current news item is active
 * </output>
 *
 */
class IfIsActiveViewHelper extends AbstractConditionViewHelper implements CompilableInterface
{
    /**
     */
    public function initializeArguments()
    {
        $this->registerArgument('newsItem', 'object', 'News item', false);
        parent::initializeArguments();
    }

    /**
     * @param array|null $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {
        $vars = GeneralUtility::_GET('tx_news_pi1');
        return isset($vars['news']) && isset($vars['newsItem']) && (int)$arguments['newsItem']->getUid() === (int)$vars['news'];
    }
}
