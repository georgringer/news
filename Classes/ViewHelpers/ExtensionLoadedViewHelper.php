<?php

namespace GeorgRinger\News\ViewHelpers;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

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
    protected static function evaluateCondition($arguments = null)
    {
        return ExtensionManagementUtility::isLoaded($arguments['extensionKey']);
    }
}
