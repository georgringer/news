<?php

namespace GeorgRinger\News\ViewHelpers\Format;

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

/**
 * ViewHelper for html_entity_decode
 *
 * @package TYPO3
 * @subpackage tx_news
 * @deprecated use Tx_Fluid_ViewHelpers_Format_HtmlentitiesDecodeViewHelper
 */
class HtmlentitiesDecodeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

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
