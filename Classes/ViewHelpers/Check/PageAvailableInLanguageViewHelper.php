<?php

namespace GeorgRinger\News\ViewHelpers\Check;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Seo\NewsAvailability;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Check if current news page is available
 */
class PageAvailableInLanguageViewHelper extends AbstractConditionViewHelper
{

    /**
     * Initialize additional argument
     */
    public function initializeArguments()
    {
        $this->registerArgument('language', 'int', 'Language ot check', true);
        parent::initializeArguments();
    }

    /**
     * @param array|null $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null): bool
    {
        try {
            $newsAvailabilityChecker = GeneralUtility::makeInstance(NewsAvailability::class);
            return $newsAvailabilityChecker->check((int)$arguments['language']);
        } catch (\UnexpectedValueException $e) {
            return true;
        }
    }

    /**
     * @return mixed
     */
    public function render()
    {
        if (static::evaluateCondition($this->arguments)) {
            return $this->renderThenChild();
        }
        return $this->renderElseChild();
    }
}
