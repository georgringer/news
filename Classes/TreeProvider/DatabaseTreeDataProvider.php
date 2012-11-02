<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Georg Ringer <typo3@ringerge.org>
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * TCA tree data provider which considers
 *
 * @author Georg Ringer <typo3@ringerge.org>
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_TreeProvider_DatabaseTreeDataProvider extends \TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeDataProvider {

	/**
	 * @param array $configuration TCA configuration
	 */
	public function __construct(array $configuration) {
	}

	/**
	 * Builds a complete node including childs
	 *
	 * @param \TYPO3\CMS\Backend\Tree\TreeNode $basicNode
	 * @param NULL|t3lib_tree_tca_DatabaseNode $parent
	 * @param integer $level
	 * @return An object
	 */
	protected function buildRepresentationForNode(\TYPO3\CMS\Backend\Tree\TreeNode $basicNode, \t3lib_tree_tca_DatabaseNode $parent = NULL, $level = 0, $restriction = FALSE) {
		$node = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('t3lib_tree_tca_DatabaseNode');
		$row = array();
		if ($basicNode->getId() == 0) {
			$node->setSelected(FALSE);
			$node->setExpanded(TRUE);
			$node->setLabel($GLOBALS['LANG']->sL($GLOBALS['TCA'][$this->tableName]['ctrl']['title']) . $GLOBALS['BE_USER']->user['tx_news_categorymounts']);
		} else {
			$row = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordWSOL($this->tableName, $basicNode->getId(), '*', '', FALSE);


			if ($this->getLabelField() !== '') {
				$node->setLabel($row[$this->getLabelField()]);
			} else {
				$node->setLabel($basicNode->getId());
			}
			$node->setSelected(\TYPO3\CMS\Core\Utility\GeneralUtility::inList($this->getSelectedList(), $basicNode->getId()));
			$node->setExpanded($this->isExpanded($basicNode));
			$node->setLabel($node->getLabel() . '+ ' . $basicNode->getId());
		}

		$node->setId($basicNode->getId());
		$node->setSelectable(!\TYPO3\CMS\Core\Utility\GeneralUtility::inList($this->getNonSelectableLevelList(), $level) && !in_array($basicNode->getId(), $this->getItemUnselectableList()));
		$node->setSortValue($this->nodeSortValues[$basicNode->getId()]);
		$node->setIcon(\TYPO3\CMS\Backend\Utility\IconUtility::mapRecordTypeToSpriteIconClass($this->tableName, $row));
		$node->setParentNode($parent);
		if ($basicNode->hasChildNodes()) {
			$node->setHasChildren(TRUE);
			$childNodes = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Tree\\SortedTreeNodeCollection');
			foreach ($basicNode->getChildNodes() as $child) {

				// Change in custom TreeDataProvider by adding the if clause
				if($restriction || TYPO3\CMS\Core\Utility\GeneralUtility::inList($GLOBALS['BE_USER']->user['tx_news_categorymounts'], $child->getId())) {
					$restriction = TRUE;
					$childNodes->append($this->buildRepresentationForNode($child, $node, $level + 1, $restriction));
				}
				// Change in custom TreeDataProvider end
			}
			$node->setChildNodes($childNodes);
		}
		return $node;
	}

}


?>