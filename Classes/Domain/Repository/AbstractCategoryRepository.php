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


	protected $uidList = array();
	protected $parentUidList = array();

	/**
	 * Set a list of category uids
	 * 
	 * @param string $categoryList comma seperated list of ids
	 */
	public function setUidList($categoryList) {
		$this->uidList = t3lib_div::intExplode(',', $categoryList, TRUE);
	}

	/**
	 * Constraint to get all categories which got a uid which is set via $this->setUidList()
	 * 
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @return Tx_Extbase_Persistence_QOM_ComparisonInterface 
	 */
	public function setUidListConstraint(Tx_Extbase_Persistence_QueryInterface $query) {
		$idList = (empty($this->uidList)) ? array('0') : $this->uidList;

		$constraint = $query->in('uid', $idList);
		return $constraint;
	}

	/**
	 * Set a list of parent category uids
	 * 
	 * @param string $categoryList comma seperated list of ids
	 */	
	public function setParentUidList($categoryList) {
		$this->parentUidList = t3lib_div::intExplode(',', $categoryList, TRUE);
	}

	/**
	 * Constraint to get all categories which are childs of categories set in $this->parentUidList
	 * 
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @return Tx_Extbase_Persistence_QOM_ComparisonInterface 
 */	
	public function setParentUidListConstraint(Tx_Extbase_Persistence_QueryInterface $query) {
		$idList = (empty($this->parentUidList)) ? array('0') : $this->parentUidList;

		$constraint = $query->in('parentcategory', $idList);
		return $constraint;
	}


}

?>