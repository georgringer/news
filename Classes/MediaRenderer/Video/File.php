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
use GeorgRinger\News\MediaRenderer\MediaInterface;
use GeorgRinger\News\Service\FileService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Implementation of file support
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class File implements MediaInterface {

	const PATH_TO_JS = 'typo3conf/ext/news/Resources/Public/JavaScript/Contrib/';

	/**
	 * Render a video player
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @param integer $width
	 * @param integer $height
	 * @param string $templateFile template file to override. Absolute path
	 * @return string
	 */
	public function render(\GeorgRinger\News\Domain\Model\Media $element, $width, $height, $templateFile = '' ) {
		$view = GeneralUtility::makeInstance('Tx_Fluid_View_StandaloneView');
		if (!$templateFile || !is_readable($templateFile)) {
			$view->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('news') . 'Resources/Private/Templates/ViewHelpers/Flv.html');
		} else {
			$view->setTemplatePathAndFilename($templateFile);
		}

		$url = FileService::getCorrectUrl($element->getContent());

		$GLOBALS['TSFE']->getPageRenderer()->addJsFile(self::PATH_TO_JS . 'flowplayer-3.2.12.min.js');

			// override width & height if both are set
		if ($element->getWidth() > 0 && $element->getHeight() > 0) {
			$width = $element->getWidth();
			$height = $element->getHeight();
		}

		$view->assign('width', MathUtility::convertToPositiveInteger($width));
		$view->assign('height', MathUtility::convertToPositiveInteger($height));
		$view->assign('uniqueDivId', 'mediaelement-' . FileService::getUniqueId($element));
		$view->assign('url', $url);

		return $view->render();
	}

	/**
	 * Files with extension flv|mp4 are handled within this implementation
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @return boolean
	 */
	public function enabled(\GeorgRinger\News\Domain\Model\Media $element) {
		$url = FileService::getFalFilename($element->getMultimedia());
		$fileEnding = strtolower(substr($url, -3));

		$enabled = FALSE;
		if ($fileEnding === 'flv' || $fileEnding === 'mp4') {
			$enabled = TRUE;
		}

		return $enabled;
	}

}

