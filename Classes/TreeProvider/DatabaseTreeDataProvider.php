<?php

namespace GeorgRinger\News\TreeProvider;

use GeorgRinger\News\Service\CategoryService;
use TYPO3\CMS\Backend\Tree\SortedTreeNodeCollection;
use TYPO3\CMS\Backend\Tree\TreeNode;
/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeNode;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TCA tree data provider which considers
 */
class DatabaseTreeDataProvider extends \TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeDataProvider
{

    /**
     * Builds a complete node including children
     *
     * @param \TYPO3\CMS\Backend\Tree\TreeNode|\TYPO3\CMS\Backend\Tree\TreeNode $basicNode
     * @param \TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeNode|null $parent
     * @param int $level
     * @param bool $restriction
     *
     * @return null|object node
     */
    protected function buildRepresentationForNode(
        TreeNode $basicNode,
        DatabaseTreeNode $parent = null,
        $level = 0,
        $restriction = false
    ): ?object {
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        /**@param $node \TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeNode */
        $node = GeneralUtility::makeInstance(DatabaseTreeNode::class);
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
        $node->setSelectable(!GeneralUtility::inList(
            $this->getNonSelectableLevelList(),
            $level
        ) && !in_array($basicNode->getId(), $this->getItemUnselectableList()));
        if (!empty($this->nodeSortValues)) {
            $node->setSortValue($this->nodeSortValues[$basicNode->getId()]);
        }
        $node->setIcon($iconFactory->getIconForRecord($this->tableName, $row, Icon::SIZE_SMALL));
        $node->setParentNode($parent);
        if ($basicNode->hasChildNodes()) {
            $node->setHasChildren(true);
            $childNodes = GeneralUtility::makeInstance(SortedTreeNodeCollection::class);
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
    protected function isCategoryAllowed($child): bool
    {
        $mounts = $GLOBALS['BE_USER']->getCategoryMountPoints();
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
    protected function isSingleCategoryAclActivated(): bool
    {
        $userTsConfig = $GLOBALS['BE_USER']->getTSConfig();
        if (is_array($userTsConfig['tx_news.'])
            && $userTsConfig['tx_news.']['singleCategoryAcl'] === '1'
        ) {
            return true;
        }

        return false;
    }
}
