<?php

declare(strict_types=1);

namespace GeorgRinger\News\ViewHelpers\MultiCategoryLink;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

/**
 * ViewHelper to check if th
 *
 * # Example: Basic example
 * <code>
 * <n:multiCategoryLink.isCategoryActive list="{overwriteDemand.categories}" item="{category.uid}">
 * do stuff
 * </n:multiCategoryLink.isCategoryActive>
 * </code>
 * <output>
 * "du stuff" will be shown if the category is currently in demand
 * </output>
 */
class IsCategoryActiveViewHelper extends AbstractConditionViewHelper implements ViewHelperInterface
{
    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('list', 'string', 'List of news', false, '');
        $this->registerArgument('item', 'int', 'Category id', true);
        parent::initializeArguments();
    }

    /**
     * @param array|null $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null): bool
    {
        if (empty($arguments['list'])) {
            return false;
        }
        return GeneralUtility::inList($arguments['list'], $arguments['item']);
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
