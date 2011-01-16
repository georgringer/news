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
 * Implementation of flv support
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Interfaces_Video_Flv implements Tx_News2_Interfaces_VideoMediaInterface {

	/**
	 * Render flv viles
	 * 
	 * @param Tx_News2_Domain_Model_Media $element
	 * @param integer $width
	 * @param integer $height
	 * @return string 
	 */
	public function render(Tx_News2_Domain_Model_Media $element, $width, $height) {
		$content = '';
		$url = $element->getVideo();

		$GLOBALS['TSFE']->getPageRenderer()->addJsFile('typo3conf/ext/news2/Resources/Public/JavaScript/flowplayer-3.2.4.min.js');
		$uniqueDivId = 'mediaelement-' . md5($element->getUid() . uniqid());

			// override width & height if both are set
		if ($element->getWidth() > 0 && $element->getHeight() > 0) {
			$width = $element->getWidth();
			$height = $element->getHeight();
		}		
		
		$content .= '<a href="' . htmlspecialchars($url) . '"
						style="display:block;width:' . (int)$width . 'px;height:' . (int)$height . 'px;"
						id="' . $uniqueDivId . '">
					</a>
					<script>
						flowplayer("' . $uniqueDivId . '", "typo3conf/ext/news2/Resources/Public/JavaScript/flowplayer-3.2.5.swf", {
							clip: {
								autoPlay: false,
								autoBuffering: true
							},
							plugins:  {
								controls:  {
									volume: true		
								}
							}
						});
					</script>
';
		


		return $content;
	}

	/**
	 *
	 * @param Tx_News2_Domain_Model_Media $element
	 * @return boolean
	 */
	public function enabled(Tx_News2_Domain_Model_Media $element) {
		$url = $element->getVideo();
		$fileEnding = strtolower(substr($url, -3));
		
		return ($fileEnding === 'flv');
	}

}

?>
