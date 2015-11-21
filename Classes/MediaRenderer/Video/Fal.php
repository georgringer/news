<?php

namespace GeorgRinger\News\MediaRenderer\Video;

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
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\MediaRenderer\FalMediaInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Implementation of fal support
 *
 */
class Fal implements FalMediaInterface
{

    const PATH_TO_JS = 'typo3conf/ext/news/Resources/Public/JavaScript/Contrib/';

    /**
     * Render a video player
     *
     * @param FileReference $element
     * @param int $width
     * @param int $height
     * @param string $templateFile template file to override. Absolute path
     * @return string
     */
    public function render(FileReference $element, $width, $height, $templateFile = '')
    {
        $view = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Fluid\View\StandaloneView');
        if (!$templateFile || !is_readable($templateFile)) {
            $view->setTemplatePathAndFilename(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news') . 'Resources/Private/Templates/ViewHelpers/Flv.html');
        } else {
            $view->setTemplatePathAndFilename($templateFile);
        }
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addJsFile(self::PATH_TO_JS . 'flowplayer-3.2.12.min.js');

        $view->assign('width', \TYPO3\CMS\Core\Utility\MathUtility::convertToPositiveInteger($width));
        $view->assign('height', \TYPO3\CMS\Core\Utility\MathUtility::convertToPositiveInteger($height));
        $view->assign('uniqueDivId', 'mediaelement-' . md5($element->getUid() . uniqid()));
        $view->assign('url', $element->getOriginalResource()->getPublicUrl());

        return $view->render();
    }

    /**
     * Files with extension flv|mp4 are handled within this implementation
     *
     * @param FileReference $element
     * @return bool
     */
    public function enabled(FileReference $element)
    {
        $fileEnding = $element->getOriginalResource()->getExtension();

        $enabled = false;
        if ($fileEnding === 'flv' || $fileEnding === 'mp4') {
            $enabled = true;
        }

        return $enabled;
    }

}

