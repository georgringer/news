<?php

namespace GeorgRinger\News\ViewHelpers\Be\Security;

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
 * ViewHelper to check if the current backend user is allowed to edit the given table
 *
 * # Example: Basic example
 * <code>
 * <n:be.security.ifAccessToTableIsAllowed table="tx_news_domain_model_news">
 *	Do something
 * </n:be.security.ifAccessToTableIsAllowed>
 * </code>
 * <output>
 * The text "do something" is only shown if the user is allowed to edit news categories
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class IfAccessToTableIsAllowedViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * Renders <f:then> child if BE user is allowed to edit given table, otherwise renders <f:else> child.
	 *
	 * @param string $table Name of the table
	 * @return string the rendered string
	 * @api
	 */
	public function render($table) {
		if ($GLOBALS['BE_USER']->isAdmin() || \TYPO3\CMS\Core\Utility\GeneralUtility::inList($GLOBALS['BE_USER']->groupData['tables_modify'], $table)) {
			return $this->renderThenChild();
		}
		return $this->renderElseChild();
	}
}
