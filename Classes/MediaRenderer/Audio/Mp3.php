<?php

namespace GeorgRinger\News\MediaRenderer\Audio;

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
use GeorgRinger\News\MediaRenderer\MediaInterface;
use GeorgRinger\News\Service\FileService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Implementation of typical audio files
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Mp3 implements MediaInterface {

	const PATH_TO_JS = 'typo3conf/ext/news/Resources/Public/JavaScript/Contrib/';

	/**
	 * Render mp3 files
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @param integer $width
	 * @param integer $height
	 * @param string $template
	 * @return string
	 */
	public function render(\GeorgRinger\News\Domain\Model\Media $element, $width, $height, $template = '') {
		$url = FileService::getCorrectUrl($element->getMultimedia());
		$uniqueId = FileService::getUniqueId($element);

		$GLOBALS['TSFE']->getPageRenderer()->addJsFile(self::PATH_TO_JS . 'swfobject-2-2.js');
		$GLOBALS['TSFE']->getPageRenderer()->addJsFile(self::PATH_TO_JS . 'audioplayer-noswfobject.js');

		$inlineJs = '
			AudioPlayer.setup("' . GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . self::PATH_TO_JS . 'audioplayer-player.swf", {
				width: ' . (int)$width . '
			});';

		$GLOBALS['TSFE']->getPageRenderer()->addJsInlineCode('news_audio', $inlineJs);

		$content = '<p id="' . htmlspecialchars($uniqueId) . '">' . htmlspecialchars($element->getCaption()) . '</p>
					<script type="text/javascript">
						AudioPlayer.embed(' . GeneralUtility::quoteJSvalue($uniqueId) . ', {soundFile: ' . GeneralUtility::quoteJSvalue($url) . '});
					</script> ';

		return $content;
	}

	/**
	 * Implementation is only used if file ending is mp3
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element media element
	 * @return boolean
	 */
	public function enabled(\GeorgRinger\News\Domain\Model\Media $element) {
		$url = FileService::getFalFilename($element->getContent());
		$fileEnding = strtolower(substr($url, -3));
		return ($fileEnding === 'mp3');
	}

}
