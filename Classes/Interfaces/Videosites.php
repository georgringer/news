<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Tx_News2_Interfaces_Videosites implements Tx_News2_Interfaces_VideoMediaInterface {

	/**
	 *
	 * @param Tx_News2_Domain_Model_Media $element
	 * @param integer $width
	 * @param integer $height
	 * @return string 
	 */
	public function render(Tx_News2_Domain_Model_Media $element, $width, $height) {
		$content = $finalUrl = '';
		$url = $element->getVideo();
			
			// get the correct rewritten url
		$mediaWizard = tslib_mediaWizardManager::getValidMediaWizardProvider($url);
		if ($mediaWizard !== NULL) {
			$finalUrl = $mediaWizard->rewriteUrl($url);
		}

		if (!empty($finalUrl)) {
			$GLOBALS['TSFE']->getPageRenderer()->addJsFile('typo3conf/ext/news2/Resources/Public/JavaScript/swfobject-2-2.js');
			$uniqueDivId = 'mediaelement-' . md5($element->getUid() . uniqid());
			
			$content .= '<div id="' . $uniqueDivId . '"></div> 
						<script type="text/javascript">
							var params = { allowScriptAccess: "always" };
							var atts = { id: "myytplayer" };
							swfobject.embedSWF("' . htmlspecialchars($url) . '", 
							"' . $uniqueDivId . '", "' . (int)$width .'", "' . (int)$height .'", "8", null, null, params, atts);
						</script>';
		}
		
		return $content;
	}

	/**
	 *
	 * @param Tx_News2_Domain_Model_Media $element
	 * @return boolean
	 */
	public function enabled(Tx_News2_Domain_Model_Media $element) {
		return TRUE;
	}

}

?>
