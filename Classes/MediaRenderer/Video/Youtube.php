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

/**
 * Implementation of youtube support
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Youtube implements MediaInterface {

	/**
	 * @var \GeorgRinger\News\Service\SettingsService
	 */
	protected $pluginSettingsService;

	/**
	 * @var \GeorgRinger\News\Service\SettingsService $pluginSettingsService
	 * @return void
	 */
	public function injectSettingsService(\GeorgRinger\News\Service\SettingsService $pluginSettingsService) {
		$this->pluginSettingsService = $pluginSettingsService;
	}

	/**
	 * Render videos from youtube
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	public function render(\GeorgRinger\News\Domain\Model\Media $element, $width, $height) {
		$content = '';

		$url = $this->getYoutubeUrl($element);

		if (!is_null($url)) {
			// override width & height if both are set
			if ($element->getWidth() > 0 && $element->getHeight() > 0) {
				$width = $element->getWidth();
				$height = $element->getHeight();
			}

			$frameBorderAttribute = '';
			if (is_object($GLOBALS['TSFE']) && $GLOBALS['TSFE']->config['config']['doctype'] !== 'html5') {
				$frameBorderAttribute = ' frameborder="0"';
			}

			$content = '<iframe width="' . (int)$width . '" height="' . (int)$height . '" src="' . htmlspecialchars($url) . '"' . $frameBorderAttribute . '></iframe>';
		}

		return $content;
	}

	/**
	 * Check if given element includes an url to a youtube video
	 *
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @return boolean
	 */
	public function enabled(\GeorgRinger\News\Domain\Model\Media $element) {
		$result = FALSE;

		$url = $this->getYoutubeUrl($element);

		if (!is_null($url)) {
			$result = TRUE;
		}
		return $result;
	}


	/**
	 * @param \GeorgRinger\News\Domain\Model\Media $element
	 * @return null|string
	 */
	public function getYoutubeUrl(\GeorgRinger\News\Domain\Model\Media $element) {
		$videoId = NULL;
		$youtubeUrl = NULL;

		if (preg_match('/(v=|v\\/|.be\\/)([^(\\&|$)]*)/', $element->getContent(), $matches)) {
			$videoId = $matches[2];
		}
		if ($videoId) {
			$youtubeUrl = '//www.youtube.com/embed/' . $videoId . '?fs=1&wmode=opaque';
			$settings = $this->pluginSettingsService->getSettings();
			if (isset($settings['mediaRenderer']) && isset($settings['mediaRenderer']['youtube'])
				&& isset($settings['mediaRenderer']['youtube']['additionalParams'])
			) {
				$youtubeUrl .= $settings['mediaRenderer']['youtube']['additionalParams'];
			}
		}

		return $youtubeUrl;
	}

}

