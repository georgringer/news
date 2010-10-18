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
 * Category Model
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Domain_Model_Category extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var Tx_News2_Domain_Model_Category
	 * @lazy
	 */
	protected $parentcategory;

	/**
	 * @var string
	 */
	protected $image;

	public function getTitle() {
	 return $this->title;
	}

	public function setTitle($title) {
	 $this->title = $title;
	}

	public function getDescription() {
	 return $this->description;
	}

	public function setDescription($description) {
	 $this->description = $description;
	}

	public function getImage() {
	 return $this->image;
	}

	public function setImage($image) {
	 $this->image = $image;
	}

	/**
	 *
	 * * @return Tx_News2_Domain_Model_Category
	 */
	public function getParentcategory() {
		if ($this->parentcategory instanceof Tx_Extbase_Persistence_LazyLoadingProxy) {
			$this->parentcategory->_loadRealInstance();
		}
	 return $this->parentcategory;
	}

	public function setParentcategory($category) {
	 $this->parentcategory = $category;
	}


}


?>