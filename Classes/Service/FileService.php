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
 * File utility
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Service_FileService {

	/**
	 * If it is an URL, nothing to do, if it is a file, check if path is allowed and prepend current url
	 *
	 * @param string $url
	 * @return string
	 * @throws UnexpectedValueException
	 */
	public static function getCorrectUrl($url) {
		if (empty($url)) {
			throw new UnexpectedValueException('An empty url is given');
		}

		$url = self::getFalFilename($url);
			// check URL
		$urlInfo = parse_url($url);

			// means: it is no external url
		if (!isset($urlInfo['scheme'])) {

				// resolve paths like ../
			$url = \TYPO3\CMS\Core\Utility\GeneralUtility::resolveBackPath($url);

				// absolute path is used to check path
			$absoluteUrl = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($url);
			if (!\TYPO3\CMS\Core\Utility\GeneralUtility::isAllowedAbsPath($absoluteUrl)) {
				throw new UnexpectedValueException('The path "' . $url . '" is not allowed.');
			}

				// append current domain
			$url = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $url;
		}

		return $url;
	}

	/**
	 * Get a unique container id
	 *
	 * @param Tx_News_Domain_Model_Media $element
	 * @return string
	 */
	public static function getUniqueId(Tx_News_Domain_Model_Media $element) {
		return 'mediaelement-' . md5($element->getUid() . uniqid());
	}

	/**
	 * If filename starts with file:, return de real path.
	 *
	 * @param @param string $url
	 * @return string
	 */

	public static function getFalFilename($url) {
		if (substr($url, 0, 5) === 'file:') {
			$fileUid = substr($url, 5);

			if (\TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($fileUid)) {
				$fileObject = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->getFileObject($fileUid);

				if ($fileObject instanceof \TYPO3\CMS\Core\Resource\FileInterface) {
					$url = $fileObject->getPublicUrl();
				}
			}
		}
		return $url;
	}

}
