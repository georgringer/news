<?php

namespace GeorgRinger\News\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class RemoveMediaTagsViewHelper extends AbstractViewHelper
{
    protected $escapingInterceptorEnabled = false;

    protected $escapeOutput = false;

    protected $escapeChildren = false;

    /**
     * @var string[]
     */
    protected $tags = ['[media]'];

    /**
     * @return mixed
     */
    public function render()
    {
        $content = $this->renderChildren();
        return str_replace($this->tags, '', $content);
    }
}
