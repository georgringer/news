<?php

namespace GeorgRinger\News\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

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
