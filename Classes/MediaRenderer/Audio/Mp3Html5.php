<?php

namespace GeorgRinger\News\MediaRenderer\Audio;

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
use GeorgRinger\News\MediaRenderer\MediaInterface;
use GeorgRinger\News\Service\FileService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Implementation of typical audio files.
 */
class Mp3Html5 implements MediaInterface
{
    const PATH_TO_JS = 'typo3conf/ext/news/Resources/Public/Contrib/audiojs/';

    /**
     * Render mp3 files.
     *
     * @param \GeorgRinger\News\Domain\Model\Media $element
     * @param int                                  $width
     * @param int                                  $height
     * @param string                               $template
     *
     * @return string
     */
    public function render(\GeorgRinger\News\Domain\Model\Media $element, $width, $height, $template = '')
    {
        $url = FileService::getCorrectUrl($element->getMultimedia());

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addJsFile(self::PATH_TO_JS.'audio.min.js');

        $inlineJs = 'audiojs.events.ready(function() { audiojs.createAll(); });';
        $pageRenderer->addJsInlineCode('news_audio_html5', $inlineJs);

        $content = '<audio src="'.htmlspecialchars($url).'" preload="auto"></audio>';

        return $content;
    }

    /**
     * Implementation is only used if file ending is mp3.
     *
     * @param \GeorgRinger\News\Domain\Model\Media $element media element
     *
     * @return bool
     */
    public function enabled(\GeorgRinger\News\Domain\Model\Media $element)
    {
        $url = FileService::getFalFilename($element->getContent());
        $fileEnding = strtolower(substr($url, -3));

        return $fileEnding === 'mp3';
    }
}
