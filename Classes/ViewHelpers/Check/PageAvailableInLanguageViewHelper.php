<?php

namespace GeorgRinger\News\ViewHelpers\Check;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Seo\NewsAvailability;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
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
    protected static function evaluateCondition($arguments = null)
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
