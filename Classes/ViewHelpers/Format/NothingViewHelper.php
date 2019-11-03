<?php

namespace GeorgRinger\News\ViewHelpers\Format;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * ViewHelper to render children which don't print out any actual content
 *
 * # Example: Basic example
 * <code>
 * <n:format.nothing>
 *    <n:titleTag>{newsItem.title}</n:titleTag>
 *    Fobar
 * </n:format.nothing>
 * </code>
 * <output>
 * nothing
 * </output>
 *
 */
class NothingViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Render children but do nothing else
     *
     */
    public function render()
    {
        $this->renderChildren();
    }
}
