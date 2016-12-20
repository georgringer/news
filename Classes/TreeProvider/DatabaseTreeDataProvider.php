<?php

namespace GeorgRinger\News\TreeProvider;

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
use GeorgRinger\News\Service\CategoryService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TCA tree data provider which considers
 */
class DatabaseTreeDataProvider extends \TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeDataProvider
{

    /**
     * @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected $backendUserAuthentication;

    /**
     * Required constructor
     *
     * @param array $configuration TCA configuration
     */
    public function __construct(array $configuration)
    {
        $this->backendUserAuthentication = $GLOBALS['BE_USER'];
    }

    /**
     * Builds a complete node including children
     *
     * @param \TYPO3\CMS\Backend\Tree\TreeNode|\TYPO3\CMS\Backend\Tree\TreeNode $basicNode
     * @param NULL|\TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeNode $parent
     * @param int $level
     * @param bool $restriction
     * @return \TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeNode node
     */
    protected function buildRepresentationForNode(
        \TYPO3\CMS\Backend\Tree\TreeNode $basicNode,
        \TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeNode $parent = null,
        $level = 0,
        $restriction = false
    ) {
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        /**@param $node \TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeNode */
        $node = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeNode::class);
        $row = [];
        if ($basicNode->getId() == 0) {
            $node->setSelected(false);
            $node->setExpanded(true);
            $node->setLabel($GLOBALS['LANG']->sL($GLOBALS['TCA'][$this->tableName]['ctrl']['title']));
        } else {
            $row = BackendUtility::getRecordWSOL($this->tableName, $basicNode->getId(), '*', '', false);

            if ($this->getLabelField() !== '') {
                $label = CategoryService::translateCategoryRecord($row[$this->getLabelField()], $row);
                $node->setLabel($label);
            } else {
                $node->setLabel($basicNode->getId());
            }
            $node->setSelected(GeneralUtility::inList($this->getSelectedList(), $basicNode->getId()));
            $node->setExpanded($this->isExpanded($basicNode));
            $node->setLabel($node->getLabel());
        }

        $node->setId($basicNode->getId());

        // Break to force single category activation
        if ($parent != null && $level != 0 && $this->isSingleCategoryAclActivated() && !$this->isCategoryAllowed($node)) {
            return null;
        }
        $node->setSelectable(!GeneralUtility::inList($this->getNonSelectableLevelList(),
                $level) && !in_array($basicNode->getId(), $this->getItemUnselectableList()));
        $node->setSortValue($this->nodeSortValues[$basicNode->getId()]);
        if (version_compare(TYPO3_branch, '8.3', '>=')) {
            $node->setIcon($iconFactory->getIconForRecord($this->tableName, $row, Icon::SIZE_SMALL));
        } else {
            $node->setIcon($iconFactory->getIconForRecord($this->tableName, $row, Icon::SIZE_SMALL)->render());
        }
        $node->setParentNode($parent);
        if ($basicNode->hasChildNodes()) {
            $node->setHasChildren(true);
            $childNodes = GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Tree\SortedTreeNodeCollection::class);
            $foundSomeChild = false;
            foreach ($basicNode->getChildNodes() as $child) {
                // Change in custom TreeDataProvider by adding the if clause
                if ($restriction || $this->isCategoryAllowed($child)) {
                    $returnedChild = $this->buildRepresentationForNode($child, $node, $level + 1, true);

                    if (!is_null($returnedChild)) {
                        $foundSomeChild = true;
                        $childNodes->append($returnedChild);
                    } else {
                        $node->setParentNode(null);
                        $node->setHasChildren(false);
                    }
                }
                // Change in custom TreeDataProvider end
            }

            if ($foundSomeChild) {
                $node->setChildNodes($childNodes);
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
    protected function isCategoryAllowed($child)
    {
        $mounts = $this->backendUserAuthentication->getCategoryMountPoints();
        if (empty($mounts)) {
            return true;
        }

        return in_array($child->getId(), $mounts);
    }

    /**
     * By setting "tx_news.singleCategoryAcl = 1" in UserTsConfig
     * every category needs to be activated, no recursive enabling
     *
     * @return bool
     */
    protected function isSingleCategoryAclActivated()
    {
        if (is_array($GLOBALS['BE_USER']->userTS['tx_news.'])
            && $GLOBALS['BE_USER']->userTS['tx_news.']['singleCategoryAcl'] === '1'
        ) {
            return true;
        }

        return false;
    }
}
