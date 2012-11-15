<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2012 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * TCA tree data provider which considers the current be_users access rights for Tx_News_Domain_Model_Category objects
 */
class Tx_News_TreeProvider_DatabaseTreeDataProvider extends t3lib_tree_Tca_DatabaseTreeDataProvider {

	/**
	 * Required constructor
	 *
	 * @param array $configuration TCA configuration
	 */
	public function __construct (array $configuration) {
	}

	/**
	 * Starts resolving of unselectable categories additionally to the default behaviour
	 *
	 * @return void
	 */
	public function initializeTreeData() {
		parent::initializeTreeData();
		$this->resolveUnselectableCategories($this->treeData);
	}

	/**
	 * Checks if the current be_user is allowed to set this node and all child nodes
	 * and adds the nodes uids to $this->itemUnselectableList if necessary.
	 *
	 * @param t3lib_tree_AbstractNode $basicNode
	 * @param boolean $parentAllowed Is set to FALSE if the user has no access rights for the parent category 
	 * @return void
	 */
	protected function resolveUnselectableCategories($basicNode, $parentAllowed = TRUE) {
		if ($basicNode === $this->treeData) {
			// Catch root object
			$categoryAllowed = FALSE;
		} elseif ($this->isCategoryInAcl($basicNode)) {
			// Category is allowed in user settings
			$categoryAllowed = TRUE;
		} elseif ($parentAllowed && !$this->isSingleCategoryAclActivated()) {
			// Parent category is allowed in user settings and category restriction inheritance is activated in UserTsConfig
			$categoryAllowed = TRUE;
		} else {
			$categoryAllowed = FALSE;
		}

		if (!$categoryAllowed) {
			$this->addItemUnselectableList($basicNode);
		}
		foreach ($basicNode->getChildNodes() as $child) {
			$this->resolveUnselectableCategories($child, $categoryAllowed);
		}
	}

	/**
	 * Adds an unselectable items uid to $this->itemUnselectableList
	 *
	 * @param t3lib_tree_AbstractNode $unselectableItem
	 * @return void
	 */
	public function addItemUnselectableList($unselectableItem) {
		if (!in_array ($unselectableItem->getId(), $this->itemUnselectableList)) {
			$this->itemUnselectableList[] = $unselectableItem->getId();
		}
	}

	/**
	 * Check if given category is allowed by the access rights
	 *
	 * @param \TYPO3\CMS\Backend\Tree\TreeNode $child
	 * @return bool
	 */
	protected function isCategoryInAcl ($child) {
		$mounts = Tx_News_Utility_CategoryProvider::getUserMounts ();
		if (empty($mounts)) {
			return TRUE;
		}

		return t3lib_div::inList ($mounts, $child->getId ());
	}

	/**
	 * By setting "tx_news.singleCategoryAcl = 1" in UserTsConfig
	 * every category needs to be activated, no recursive enabling
	 *
	 * @return bool
	 */
	protected function isSingleCategoryAclActivated() {
		if (is_array($GLOBALS['BE_USER']->userTS['tx_news.'])
			&& $GLOBALS['BE_USER']->userTS['tx_news.']['singleCategoryAcl'] === '1'
		) {
			return TRUE;
		}

		return FALSE;
	}

}

?>