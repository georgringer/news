<?php

namespace GeorgRinger\News\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Class ExtensionLoadedViewHelper
 */
class ExtensionLoadedViewHelper extends AbstractConditionViewHelper
{

    /**
     * Initialize additional argument
     */
    public function initializeArguments()
    {
        $this->registerArgument('extensionKey', 'string', 'Extension which must be checked', true);
        parent::initializeArguments();
    }

    /**
     * @param array|null $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null): bool
    {
        return ExtensionManagementUtility::isLoaded($arguments['extensionKey']);
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
