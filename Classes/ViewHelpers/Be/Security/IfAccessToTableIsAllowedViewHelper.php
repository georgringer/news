<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
class Tx_News_ViewHelpers_Be_Security_IfAccessToTableIsAllowedViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

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
