<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\ViewHelpers\Check;

use GeorgRinger\News\Seo\NewsAvailability;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Check if current news page is available
 */
class PageAvailableInLanguageViewHelper extends AbstractConditionViewHelper
{
    /**
     * Initialize additional argument
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('language', 'int', 'Language ot check', true);
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        try {
            $newsAvailabilityChecker = GeneralUtility::makeInstance(NewsAvailability::class);
            return $newsAvailabilityChecker->check((int)$arguments['language']);
        } catch (\UnexpectedValueException) {
            return true;
        }
    }
}
