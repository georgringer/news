<?php

declare(strict_types=1);
/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\ViewHelpers;

use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

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
 */
class IfIsActiveViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('newsItem', 'object', 'News item', false);
        parent::initializeArguments();
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext)
    {
        /** @var PageArguments $routing */
        $routing = $renderingContext->getRequest()?->getAttribute('routing');
        return $routing && (int)($routing->getArguments()['tx_news_pi1']['news'] ?? 0) === $arguments['newsItem']->getUid();
    }
}
