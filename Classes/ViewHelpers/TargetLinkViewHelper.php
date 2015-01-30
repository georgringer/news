<?php

namespace GeorgRinger\News\ViewHelpers;

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
class TargetLinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

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
