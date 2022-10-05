<?php

namespace GeorgRinger\News\ViewHelpers\Be;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

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
    public function render(): string
    {
        return (isset($this->arguments['categories']) && is_array($this->arguments['categories']) && in_array($this->arguments['id'], $this->arguments['categories'])) ? 'checked="checked"' : '';
    }
}
