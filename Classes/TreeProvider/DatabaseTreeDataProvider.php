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
	 * @var Tx_News_Domain_Model_Dto_EmConfiguration
	 */
	protected $emConfiguration;

	/**
	 * Required constructor
	 *
	 * @param array $configuration TCA configuration
	 */
	public function __construct (array $configuration) {
		$this->emConfiguration = Tx_News_Utility_EmConfiguration::getSettings();
	}

	/**
	 * Starts resolving of unselectable categories additionally to the default behaviour
	 *
	 * @return void
	 */
	public function initializeTreeData() {
		parent::initializeTreeData();
		$this->treeData = $this->resolveUnselectableCategories($this->treeData);
	}

	/**
	 * Checks if the current be_user is allowed to access this category or a subcategory. If
	 * he's not and the extension configuration removeInaccessibleCategorySubtrees is set to TRUE,
	 * the category will be deleted. If he has only access to a subcategory, the
	 * assigned category will be added to $this->itemUnselectableList.
	 *
	 * @param t3lib_tree_AbstractNode $basicNode
	 * @return t3lib_tree_AbstractNode
	 */
	protected function resolveUnselectableCategories(&$basicNode) {
		if (Tx_News_Utility_TreeNode::isCategoryInAcl($basicNode)) {
			// If singleCategoryAcl is deactivated stop resolving because this node gives access to all child nodes.
			if (!Tx_News_Utility_TreeNode::isSingleCategoryAclActivated()) return $basicNode;
		}
		else {
			$this->addItemUnselectableList($basicNode);
		}

		if ($basicNode->hasChildNodes()) {
			$newChildNodesArray = array('serializeClassName' => 't3lib_tree_NodeCollection');
			foreach ($basicNode->getChildNodes() as $child) {
				if (!$this->emConfiguration->getRemoveInaccessibleCategorySubtrees() || !Tx_News_Utility_TreeNode::canNodeBeRemoved($child)) {
					$newChildNodesArray[] = $this->resolveUnselectableCategories($child)->toArray();
				}
			}
			if (count($newChildNodesArray) === 1) {
				$basicNode->removeChildNodes();
			}
			else {
				$newChildNodes = t3lib_div::makeInstance('t3lib_tree_NodeCollection', $newChildNodesArray);
				$basicNode->setChildNodes($newChildNodes);
			}
		}
		return $basicNode;
	}

	/**
	 * Adds an unselectable item uid to $this->itemUnselectableList
	 *
	 * @param t3lib_tree_AbstractNode $unselectableItem
	 * @return void
	 */
	public function addItemUnselectableList($unselectableItem) {
		if (!in_array ($unselectableItem->getId(), $this->itemUnselectableList)) {
			$this->itemUnselectableList[] = $unselectableItem->getId();
		}
	}

}

?>