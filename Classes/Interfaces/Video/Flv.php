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
 * @subpackage tx_news
 */
class Tx_News_Interfaces_Video_Flv implements Tx_News_Interfaces_MediaInterface {

	/**
	 * Render flv viles
	 *
	 * @param Tx_News_Domain_Model_Media $element
	 * @param integer $width
	 * @param integer $height
	 * @param string $templateFile template file to override. Absolute path
	 * @return string
	 */
	public function render(Tx_News_Domain_Model_Media $element, $width, $height, $templateFile = '' ) {
		$view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
		if (!$templateFile || !is_readable($templateFile)) {
			$view->setTemplatePathAndFilename(t3lib_extMgm::extPath('news').'Resources/Private/Templates/ViewHelpers/Flv.html');
		} else {
			$view->setTemplatePathAndFilename($templateFile);
		}

		$url = Tx_News_Service_FileService::getCorrectUrl($element->getContent());

		$GLOBALS['TSFE']->getPageRenderer()->addJsFile('typo3conf/ext/news/Resources/Public/JavaScript/Contrib/flowplayer-3.2.4.min.js');

			// override width & height if both are set
		if ($element->getWidth() > 0 && $element->getHeight() > 0) {
			$width = $element->getWidth();
			$height = $element->getHeight();
		}

		$view->assign('width', Tx_News_Utility_Compatibility::convertToPositiveInteger($width));
		$view->assign('height', Tx_News_Utility_Compatibility::convertToPositiveInteger($height));
		$view->assign('uniqueDivId', 'mediaelement-' . md5($element->getUid() . uniqid()));
		$view->assign('url', htmlspecialchars($url));

		return $view->render();
	}

	/**
	 * Files with extension flv are handled within this implementation
	 *
	 * @param Tx_News_Domain_Model_Media $element
	 * @return boolean
	 */
	public function enabled(Tx_News_Domain_Model_Media $element) {
		$url = $element->getMultimedia();
		$fileEnding = strtolower(substr($url, -3));

		return ($fileEnding === 'flv');
	}

}

?>