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
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Interfaces_Audio_Mp3 implements Tx_News2_Interfaces_MediaInterface {

	/**
	 * Render flv viles
	 *
	 * @param Tx_News2_Domain_Model_Media $element
	 * @param integer $width
	 * @param integer $height
	 * @param string $template
	 * @return string
	 */
	public function render(Tx_News2_Domain_Model_Media $element, $width, $height, $template = '') {
		$url = Tx_News2_Service_FileService::getCorrectUrl($element->getVideo());
		$url = htmlspecialchars($url);
		$uniqueId = Tx_News2_Service_FileService::getUniqueId($element);

		$GLOBALS['TSFE']->getPageRenderer()->addJsFile('typo3conf/ext/news2/Resources/Public/JavaScript/Contrib/swfobject-2-2.js');
		$GLOBALS['TSFE']->getPageRenderer()->addJsFile('typo3conf/ext/news2/Resources/Public/JavaScript/Contrib/audioplayer-noswfobject.js');

		$inlineJs = '
			AudioPlayer.setup("' . t3lib_div::getIndpEnv('TYPO3_SITE_URL') . 'typo3conf/ext/news2/Resources/Public/JavaScript/Contrib/audioplayer-player.swf", {
				width: ' . (int)$width . '
			});';

		$GLOBALS['TSFE']->getPageRenderer()->addJsInlineCode('news2_audio', $inlineJs);

		$content = '<p id="' . $uniqueId . '">' . htmlspecialchars($element->getCaption()) . '</p>
					<script type="text/javascript">
						AudioPlayer.embed("' . $uniqueId . '", {soundFile: "' . $url . '"});
					</script> ';

		return $content;
	}

	/**
	 * Implementation is only used if file ending is mp3
	 * 
	 * @param Tx_News2_Domain_Model_Media $element media element
	 * @return boolean
	 */
	public function enabled(Tx_News2_Domain_Model_Media $element) {
		$url = $element->getVideo();
		$fileEnding = strtolower(substr($url, -3));

		return ($fileEnding === 'mp3');
	}

}

?>