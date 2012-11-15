<?php
/**
 * Utility class providing helper methods for the DatabaseTreeDataProvider
 */
Class Tx_News_Utility_TreeNode {

	/**
	 * Checks whether the assigned node or any child node is accessible for the user
	 *
	 * @param t3lib_tree_AbstractNode $basicNode
	 * @return boolean
	 */
	public static function canNodeBeRemoved($basicNode) {
		if (self::isCategoryInAcl($basicNode)) {
			return FALSE;
		}
		if ($basicNode->hasChildNodes()) {
			foreach ($basicNode->getChildNodes() as $child) {
				if (!self::canNodeBeRemoved($child)) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	/**
	 * Check if given category is allowed by the access rights
	 *
	 * @param t3lib_tree_AbstractNode $child
	 * @return boolean
	 */
	public static function isCategoryInAcl($child) {
		$mounts = Tx_News_Utility_CategoryProvider::getUserMounts();
		if (empty($mounts)) {
			return TRUE;
		}
		return t3lib_div::inList($mounts, $child->getId());
	}

	/**
	 * By setting "tx_news.singleCategoryAcl = 1" in UserTsConfig
	 * every category needs to be activated, no recursive enabling
	 *
	 * @return boolean
	 */
	public static function isSingleCategoryAclActivated() {
		if (is_array($GLOBALS['BE_USER']->userTS['tx_news.']) && $GLOBALS['BE_USER']->userTS['tx_news.']['singleCategoryAcl'] === '1') {
			return TRUE;
		}
		return FALSE;
	}
}
?>