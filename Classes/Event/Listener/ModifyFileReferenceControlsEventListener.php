<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event\Listener;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Backend\Form\Event\ModifyFileReferenceControlsEvent;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ModifyFileReferenceControlsEventListener
{
    public function modifyControls(
        ModifyFileReferenceControlsEvent $event
    ): void {
        $childRecord = $event->getRecord();
        $previewSetting = (int)(is_array($childRecord['showinpreview'] ?? false) ? $childRecord['showinpreview'][0] : ($childRecord['showinpreview'] ?? 0));
        if ($event->getForeignTable() === 'sys_file_reference' && $previewSetting > 0) {
            $ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

            $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
            $extensionConfiguration = GeneralUtility::makeInstance(EmConfiguration::class);

            if ($extensionConfiguration->isAdvancedMediaPreview()) {
                if ($previewSetting === 1) {
                    $icon = $iconFactory->getIcon('ext-news-doublecheck', Icon::SIZE_SMALL);
                    $label = $GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.showinviews.1');
                    $event->setControl('ext-news-preview', ' <span class="btn btn-default" title="' . htmlspecialchars($label) . '">' . $icon . '</span>');
                } elseif ($previewSetting === 2) {
                    $icon = $iconFactory->getIcon('actions-check', Icon::SIZE_SMALL);
                    $label = $GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.showinviews.2');
                    $event->setControl('ext-news-preview', ' <span class="btn btn-default" title="' . htmlspecialchars($label) . '">' . $icon . '</span>');
                }
            } elseif ($previewSetting === 1) {
                $icon = $iconFactory->getIcon('actions-check', Icon::SIZE_SMALL);
                $label = $GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.showinpreview');
                $event->setControl('ext-news-preview', ' <span class="btn btn-default" title="' . htmlspecialchars($label) . '">' . $icon . '</span>');
            }
        }
    }
}
