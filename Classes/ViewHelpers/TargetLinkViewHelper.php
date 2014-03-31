<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
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
 * ViewHelper to get the target out of the typolink
 *
 * # Example: Basic Example
 * # Description: {relatedLink.uri} is defined as "123 _blank"
 * <code>
 * <f:link.page pageUid="{relatedLink.uri}" target="{n:targetLink(link:relatedLink.uri)}">Link</Link>
 * </code>
 * <output>
 * A link to the page with uid 123 and target set to "_blank"
 * </output>
 */
class Tx_News_ViewHelpers_TargetLinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Returns the correct target of a typolink
	 *
	 * @param string $link
	 * @return string
	 */
	public function render($link) {
		$params = explode(' ', $link);

		// The target is on the 2nd place and must start with a '_'
		if (count($params) >= 2 && substr($params[1], 0, 1) === '_') {
			return $params[1];
		}

		return '';
	}

}
