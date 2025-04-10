<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Hooks\PluginPreviewRenderer;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;

final class PluginPreviewSummaryEvent
{
    protected GridColumnItem $item;
    protected PluginPreviewRenderer $pluginPreviewRenderer;

    public function __construct(GridColumnItem $item, PluginPreviewRenderer $pluginPreviewRenderer)
    {
        $this->item = $item;
        $this->pluginPreviewRenderer = $pluginPreviewRenderer;
    }

    public function getItem(): GridColumnItem
    {
        return $this->item;
    }

    public function getPluginPreviewRenderer(): PluginPreviewRenderer
    {
        return $this->pluginPreviewRenderer;
    }

}
