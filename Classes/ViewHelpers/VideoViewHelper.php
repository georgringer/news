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
 * ViewHelper to show videos
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_ViewHelpers_VideoViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {


	/**
	 * Show a video file
	 * 
	 * @param string $url
	 * @param string $caption
	 * @param integer $width
	 * @param integer $height
	 * @param array $configuration
	 * @return string
	 */
	public function render($url, $caption = '', $width, $height,  array $configuration = array()) {
		$this->width = $width;
		$this->height = $height;


		$mediaWizard = tslib_mediaWizardManager::getValidMediaWizardProvider($url);
//
		if ($mediaWizard !== NULL) {
			$url = $mediaWizard->rewriteUrl($url);
		}

		if (!$url) {
			throw new Exception('what to do with this url, could be relative, @todo *gg*');
		}


//		$video = $this->renderVideoWithCobjMedia($url, $configuration);
		$video = $this->renderVideoWithJquery($url, $caption);

		return $video;
	}

	
	protected function renderVideoWithCobjMedia($url, array $optionalConfiguration = array()) {
		$cObj = t3lib_div::makeInstance('tslib_cObj');

//		$defaultConfiguration = array(
//			'file' => $url,
//			'renderType' => 'auto',
//			'width' => 100,
//			'height' => 100,
//			'forcePlayer' => 1,
//			'layout' => '###SWFOBJECT###'
//		);
//
//			// merge default configuration with optional configuration
//		$configuration = t3lib_div::array_merge_recursive_overrule($defaultConfiguration, $optionalConfiguration);
//
//		$video = $cObj->MEDIA($configuration);


		$video = '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
<script type="text/javascript" src="http://github.com/malsup/media/raw/master/jquery.media.js?v0.92"></script>
<script type="text/javascript" src="http://jquery.malsup.com/jquery.metadata.v2.js"></script>
<script type="text/javascript">
    $(function() {

        // this one liner handles all the examples on this page
        $("a.media").media();
    });
</script>
';

		$video .= '<a class="media {width:450, height:380, type:\'swf\'}" href="' . $url . '">Foar</a> ';

		return $video;
	}

	protected function renderVideoWithJquery($url, $caption) {
		$js = '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
<script type="text/javascript" src="http://github.com/malsup/media/raw/master/jquery.media.js?v0.92"></script>
<script type="text/javascript" src="http://jquery.malsup.com/jquery.metadata.v2.js"></script>
<script type="text/javascript">
    $(function() {

        // this one liner handles all the examples on this page
        $("a.media").media();
    });
</script>
';

//		$GLOBALS['TSFE']->getPageRenderer()->addHeaderData($js);
		$video = $js;

		$video .= '<a class="media {width:' . $this->width . ', height:' . $this->height . ', type:\'swf\'}" href="' . $url . '">' . $caption . '</a> ';

		return $video;
	}

}