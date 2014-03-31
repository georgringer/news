<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Bastian Waidelich <bastian@typo3.org>
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * ViewHelper for html_entity_decode
 *
 * @package TYPO3
 * @subpackage tx_news
 * @deprecated use Tx_Fluid_ViewHelpers_Format_HtmlentitiesDecodeViewHelper
 */
class Tx_News_ViewHelpers_Format_HtmlentitiesDecodeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Disable the escaping interceptor because otherwise the
	 * child nodes would be escaped before this view helper
	 * can decode the text's entities.
	 *
	 * @var boolean
	 */
	protected $escapingInterceptorEnabled = FALSE;

	/**
	 * Converts all HTML entities to their applicable characters as needed
	 * using PHPs html_entity_decode() function.
	 *
	 * @param string $value string to format
	 * @param boolean $keepQuotes if TRUE, single and double quotes won't be replaced
	 * @return string the altered string
	 * @see http://www.php.net/html_entity_decode
	 */
	public function render($value = NULL, $keepQuotes = FALSE) {
		if (class_exists('Tx_Fluid_ViewHelpers_Format_HtmlentitiesDecodeViewHelper')) {
			$message = 'EXT:news: Since TYPO3 4.6.0, a native ViewHelper for html_entity_decode() ' .
				'is available, use f:format.htmlentitiesDecode instead of n:format.htmlEntityDecode';

			\TYPO3\CMS\Core\Utility\GeneralUtility::deprecationLog($message);
		}

		if ($value === NULL) {
			$value = $this->renderChildren();
		}
		if (!is_string($value)) {
			return $value;
		}
		$flags = $keepQuotes ? ENT_NOQUOTES : ENT_COMPAT;
		return html_entity_decode($value, $flags);
	}

}
