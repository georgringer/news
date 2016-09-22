<?php

namespace GeorgRinger\News\ViewHelpers\Be;

/*
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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Check if the checkbox should be active or not
 */
class IsCheckboxActiveViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('id', 'int', 'Category id', true);
        $this->registerArgument('categories', 'array', 'List of categories', false, []);
    }

    /**
     * @return string
     */
    public function render()
    {
        return (is_array($this->arguments['categories']) && in_array($this->arguments['id'], $this->arguments['categories'])) ? 'checked="checked"' : '';
    }
}
