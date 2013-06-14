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
 * TCA tree data provider which considers
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
	 * Builds a complete node including children
	 *
	 * @param \t3lib_tree_Node|\TYPO3\CMS\Backend\Tree\TreeNode $basicNode
	 * @param NULL|t3lib_tree_tca_DatabaseNode $parent
	 * @param integer $level
	 * @param bool $restriction
	 * @return t3lib_tree_tca_DatabaseNode node
	 */
	protected function buildRepresentationForNode (t3lib_tree_Node $basicNode, t3lib_tree_tca_DatabaseNode $parent = NULL, $level = 0, $restriction = FALSE) {
		/**@param $node t3lib_tree_tca_DatabaseNode */
		$node = t3lib_div::makeInstance ('t3lib_tree_tca_DatabaseNode');
		$row = array();
		if ($basicNode->getId () == 0) {
			$node->setSelected (FALSE);
			$node->setExpanded (TRUE);
			$node->setLabel ($GLOBALS['LANG']->sL ($GLOBALS['TCA'][$this->tableName]['ctrl']['title']));
		} else {
			$row = t3lib_BEfunc::getRecordWSOL ($this->tableName, $basicNode->getId (), '*', '', FALSE);

			if ($this->getLabelField () !== '') {
				$label = Tx_News_Service_CategoryService::translateCategoryRecord($row[$this->getLabelField()], $row);
				$node->setLabel($label);
			} else {
				$node->setLabel ($basicNode->getId ());
			}
			$node->setSelected (t3lib_div::inList ($this->getSelectedList (), $basicNode->getId ()));
			$node->setExpanded ($this->isExpanded ($basicNode));
			$node->setLabel ($node->getLabel ());
		}

		$node->setId ($basicNode->getId ());

		// Break to force single category activation
		if ($parent != NULL && $level != 0 && $this->isSingleCategoryAclActivated() && !$this->isCategoryAllowed ($node)) {
			return NULL;
		}
		$node->setSelectable (!t3lib_div::inList ($this->getNonSelectableLevelList (), $level) && !in_array ($basicNode->getId (), $this->getItemUnselectableList ()));
		$node->setSortValue ($this->nodeSortValues[$basicNode->getId ()]);
		$node->setIcon (t3lib_iconWorks::mapRecordTypeToSpriteIconClass ($this->tableName, $row));
		$node->setParentNode ($parent);
		if ($basicNode->hasChildNodes ()) {
			$node->setHasChildren (TRUE);
			$childNodes = t3lib_div::makeInstance ('t3lib_tree_SortedNodeCollection');
			$foundSomeChild = FALSE;
			foreach ($basicNode->getChildNodes () as $child) {
				// Change in custom TreeDataProvider by adding the if clause
				if ($restriction || $this->isCategoryAllowed ($child)) {
					$returnedChild = $this->buildRepresentationForNode ($child, $node, $level + 1, TRUE);

					if (!is_null ($returnedChild)) {
						$foundSomeChild = TRUE;
						$childNodes->append ($returnedChild);
					} else {
						$node->setParentNode (NULL);
						$node->setHasChildren (FALSE);
					}
				}
				// Change in custom TreeDataProvider end
			}

			if ($foundSomeChild) {
				$node->setChildNodes ($childNodes);
			}
		}
		return $node;
	}

	/**
	 * Check if given category is allowed by the access rights
	 *
	 * @param \TYPO3\CMS\Backend\Tree\TreeNode $child
	 * @return bool
	 */
	protected function isCategoryAllowed ($child) {
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