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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Implementation of video portal support
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Videosites implements MediaInterface {

	/**
	 * Render videos from various video portals
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	public function render(\GeorgRinger\News\Domain\Model\Media $element, $width, $height) {
		$content = $finalUrl = '';
		$url = FileService::getCorrectUrl($element->getContent());

			// get the correct rewritten url
		$mediaWizard = \TYPO3\CMS\Frontend\MediaWizard\MediaWizardProviderManager::getValidMediaWizardProvider($url);
		if ($mediaWizard !== NULL) {
			$finalUrl = $mediaWizard->rewriteUrl($url);
		}

			// override width & height if both are set
		if ($element->getWidth() > 0 && $element->getHeight() > 0) {
			$width = $element->getWidth();
			$height = $element->getHeight();
		}

		if (!empty($finalUrl)) {
			$GLOBALS['TSFE']->getPageRenderer()->addJsFile('typo3conf/ext/news/Resources/Public/JavaScript/Contrib/swfobject-2-2.js');
			$uniqueDivId = 'mediaelement' . FileService::getUniqueId($element);

			$content .= '<div id="' . htmlspecialchars($uniqueDivId) . '"></div>
						<script type="text/javascript">
							var params = { allowScriptAccess: "always", allowfullscreen : "true" };
							var atts = { id: ' . GeneralUtility::quoteJSvalue($uniqueDivId) . ' };
							swfobject.embedSWF(' . GeneralUtility::quoteJSvalue($finalUrl) . ',
							' . GeneralUtility::quoteJSvalue($uniqueDivId) . ', "' . (int)$width . '", "' . (int)$height . '", "8", null, null, params, atts);
						</script>';
		}

		return $content;
	}

	/**
	 * Videosites implementation is always enabled if any file given,
	 * the check is done in tslib_mediaWizardManager
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @return boolean
	 */
	public function enabled(\GeorgRinger\News\Domain\Model\Media $element) {
		$result = TRUE;
		$file = $element->getContent();
		if (empty($file)) {
			$result = FALSE;
		}
		return $result;
	}

}
