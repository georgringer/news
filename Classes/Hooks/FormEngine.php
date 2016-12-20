<?php

namespace GeorgRinger\News\Hooks;

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
use GeorgRinger\News\Service\AccessControlService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into FormEngine
 *
 */
class FormEngine
{

    /**
     * Path to the locallang file
     *
     * @var string
     */
    const LLPATH = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:';

    /**
     * Pre-processing of the whole TCEform
     *
     * @param string $table
     * @param array $row
     * @param \TYPO3\CMS\Backend\Form\FormEngine $parentObject
     * @todo this hook won't work, do we need it?
     */
    public function getMainFields_preProcess($table, $row, $parentObject)
    {
        if ($table !== 'tx_news_domain_model_news') {
            return;
        }
        if (!AccessControlService::userHasCategoryPermissionsForRecord($row)) {
            if (method_exists($parentObject, 'setRenderReadonly')) {
                $parentObject->setRenderReadonly(true);
            } else {
                $parentObject->renderReadonly = true;
            }
            $flashMessageContent = htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'record.savingdisabled.content'));
            $flashMessageContent .= '<ul>';
            $accessDeniedCategories = AccessControlService::getAccessDeniedCategories($row);
            foreach ($accessDeniedCategories as $accessDeniedCategory) {
                $flashMessageContent .= '<li>' . htmlspecialchars($accessDeniedCategory['title']) . ' [' . $accessDeniedCategory['uid'] . ']</li>';
            }
            $flashMessageContent .= '</ul>';

            $flashMessage = GeneralUtility::makeInstance(
                'TYPO3\CMS\Core\Messaging\FlashMessage',
                $flashMessageContent,
                htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'record.savingdisabled.header')),
                FlashMessage::WARNING
            );

            $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
            $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
            $defaultFlashMessageQueue->enqueue($flashMessage);
        }
    }
}
