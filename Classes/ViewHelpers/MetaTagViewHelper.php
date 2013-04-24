<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2010 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * ViewHelper to render meta tags
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_MetaTagViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'meta';

	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('property', 'string', 'Property of meta tag');
		$this->registerTagAttribute('content', 'string', 'Content of meta tag');
	}


	/**
	 * Renders a meta tag
	 *
	 * @param boolean $useCurrentDomain If set, current domain is used
	 * @param boolean $forceAbsoluteUrl If set, absolute url is forced
	 * @param boolean $useNameAttribute If set, the meta tag is built by using the attribute name="" instead of property
	 * @return void
	 */
	public function render($useCurrentDomain = FALSE, $forceAbsoluteUrl = FALSE, $useNameAttribute = FALSE) {
		// set current domain
		if ($useCurrentDomain) {
			$this->tag->addAttribute('content', t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'));
		}

		// prepend current domain
		if ($forceAbsoluteUrl) {
			$path = $this->arguments['content'];
			if (!t3lib_div::isFirstPartOfStr($path, t3lib_div::getIndpEnv('TYPO3_SITE_URL'))) {
				$this->tag->addAttribute('content', t3lib_div::getIndpEnv('TYPO3_SITE_URL') . $this->arguments['content']);
			}
		}

		if ($useCurrentDomain || (isset($this->arguments['content']) && !empty($this->arguments['content']))) {
			if ($useNameAttribute) {
				$attributesContent = $this->tag->getAttribute('property');
				$this->tag->removeAttribute('property');
				$this->tag->addAttribute('name', $attributesContent);
			}
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag($this->tag->render());
		}
	}
}

?>