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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to check if the current news item is rendered as single view on the same page
 *
 * # Example: Basic example
 * <code>
 * {n:ifIsActive(newsItem: newsItem, then: 'active', else: '')}
 * </code>
 * <output>
 * Renders the string "active" if the current news item is active
 * </output>
 *
 */
class IfIsActiveViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	public function render(\GeorgRinger\News\Domain\Model\News $newsItem) {
		$vars = GeneralUtility::_GET('tx_news_pi1');

		if (isset($vars['news']) && (int)$newsItem->getUid() === (int)$vars['news']) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}
