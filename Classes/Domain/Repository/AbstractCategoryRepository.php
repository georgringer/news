<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Abstract category repository which does all the fancy stuff
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Domain_Repository_AbstractCategoryRepository extends Tx_News2_Domain_Repository_AbstractRepository {


	protected $fetchedCategories = array();



	/**
	 * Recursive function to create category menu
	 *
	 * @param  integer $parentId parent category id
	 * @return array
	 */
	public function getRecursiveCategories($parentId, $level=0) {
		$out = array();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*', #uid,title,parentcategory
			'tx_news2_domain_model_category',
			'sys_language_uid = 0 AND deleted=0 AND hidden=0 AND parentcategory=' . $parentId
		);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$uid = $row['uid'];
			if (!isset($this->fetchedCategories[$uid])) {
				$this->fetchedCategories[$uid] = 1;
				$out[$uid] = $row;

				if ($level <=2) {
					$out[$uid]['sub'] = $this->getRecursiveCategories($uid, $level++);
				}
			}

		}

		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $out;

	}

}


?>