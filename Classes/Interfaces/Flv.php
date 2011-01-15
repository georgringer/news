<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Tx_News2_Interfaces_Flv implements Tx_News2_Interfaces_VideoMediaInterface {

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
		
//		$content .= $url;
		$url = 'http://pseudo01.hddn.com/vod/demo.flowplayervod/flowplayer-700.flv';

		$GLOBALS['TSFE']->getPageRenderer()->addJsFile('typo3conf/ext/news2/Resources/Public/JavaScript/flowplayer-3.2.4.min.js');
		$uniqueDivId = 'mediaelement-' . md5($element->getUid() . uniqid());

		$content .= '<a href="' . htmlspecialchars($url) . '"
						style="display:block;width:' . (int)$width . 'px;height:' . (int)$height . 'px;"
						id="' . $uniqueDivId . '">
					</a>
					<script>
						flowplayer("' . $uniqueDivId . '", "typo3conf/ext/news2/Resources/Public/JavaScript/flowplayer-3.2.5.swf", {
							plugins:  {
								controls:  {
									volume: false		
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
