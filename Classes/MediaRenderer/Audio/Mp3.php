<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Implementation of typical audio files
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_MediaRenderer_Audio_Mp3 implements Tx_News_MediaRenderer_MediaInterface {

	const PATH_TO_JS = 'typo3conf/ext/news/Resources/Public/JavaScript/Contrib/';

	/**
	 * Render mp3 files
	 *
	 * @param Tx_News_Domain_Model_Media $element
	 * @param integer $width
	 * @param integer $height
	 * @param string $template
	 * @return string
	 */
	public function render(Tx_News_Domain_Model_Media $element, $width, $height, $template = '') {
		$url = Tx_News_Service_FileService::getCorrectUrl($element->getMultimedia());
		$uniqueId = Tx_News_Service_FileService::getUniqueId($element);

		$GLOBALS['TSFE']->getPageRenderer()->addJsFile(self::PATH_TO_JS . 'swfobject-2-2.js');
		$GLOBALS['TSFE']->getPageRenderer()->addJsFile(self::PATH_TO_JS . 'audioplayer-noswfobject.js');

		$inlineJs = '
			AudioPlayer.setup("' . \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . self::PATH_TO_JS . 'audioplayer-player.swf", {
				width: ' . (int)$width . '
			});';

		$GLOBALS['TSFE']->getPageRenderer()->addJsInlineCode('news_audio', $inlineJs);

		$content = '<p id="' . htmlspecialchars($uniqueId) . '">' . htmlspecialchars($element->getCaption()) . '</p>
					<script type="text/javascript">
						AudioPlayer.embed(' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue($uniqueId) . ', {soundFile: ' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue($url) . '});
					</script> ';

		return $content;
	}

	/**
	 * Implementation is only used if file ending is mp3
	 *
	 * @param Tx_News_Domain_Model_Media $element media element
	 * @return boolean
	 */
	public function enabled(Tx_News_Domain_Model_Media $element) {
		$url = Tx_News_Service_FileService::getFalFilename($element->getContent());
		$fileEnding = strtolower(substr($url, -3));

		return ($fileEnding === 'mp3');
	}

}
