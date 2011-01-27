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
 * Do some file infos
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Service_FileService {
	
	/**
	 * If it is an URL, nothing to do, if it is a file, check if path is allowed and prepend current url
	 * 
	 * @param string $url
	 * @return string 
	 */
	public function getCorrectUrl($url) {
		if (empty($url)) {
			throw new Exception('An empty url is given');
		}
			// check URL
		$urlInfo = parse_url($url);
		
			// means: it is no external url
		if (!isset($urlInfo['scheme'])) {
			
				// resolve paths like ../
			$url = t3lib_div::resolveBackPath($url);
			
				// absolute path is used to check path
			$absoluteUrl = t3lib_div::getFileAbsFileName($url);
			if (!t3lib_div::isAllowedAbsPath($absoluteUrl)) {
				throw new Exception('The path "' . $url . '" is not allowed.');
			}
			
				// append current domain
			$url = t3lib_div::getIndpEnv('TYPO3_SITE_URL') . $url;
		}		
		
		return $url;
	}
	
	/**
	 * Get a unique container id
	 * 
	 * @param Tx_News2_Domain_Model_Media $element
	 * @return string
	 */
	public function getUniqueId(Tx_News2_Domain_Model_Media $element) {
		return 'mediaelement-' . md5($element->getUid() . uniqid());
	}
	
}
?>