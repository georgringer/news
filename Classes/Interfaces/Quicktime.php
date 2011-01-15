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
 * Implementation of quicktime support
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Interfaces_Quicktime implements Tx_News2_Interfaces_VideoMediaInterface {

	/**
	 * Render flv viles
	 * 
	 * @param Tx_News2_Domain_Model_Media $element
	 * @param integer $width
	 * @param integer $height
	 * @return string 
	 */
	public function render(Tx_News2_Domain_Model_Media $element, $width, $height) {
		$url = htmlspecialchars($element->getVideo());
		
		$width = (int)$width;
		$height = (int)$height;

		$content = 
			'<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="' . $width . '" height="' . $height . '" >
              <param name="src" value="' . $url . '">
              <param name="autoplay" value="true">
              <param name="type" value="video/quicktime" width="' . $width . '" height="' . $height . '">      
              <embed src="' . $url . '" width="' . $width . '" height="' . $height . '" autoplay="false" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/">
            </object>';
			

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
		
		return ($fileEnding === 'mov');
	}

}

?>
