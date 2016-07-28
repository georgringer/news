<?php

namespace GeorgRinger\News\ViewHelpers\Format;

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
class NothingViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Render children but do nothing else
     *
     * @return void
     */
    public function render()
    {
        $this->renderChildren();
    }
}
