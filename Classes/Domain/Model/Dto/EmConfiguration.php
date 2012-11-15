<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * Extension Manager configuration
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Domain_Model_Dto_EmConfiguration {

	/**
	 * Fill the properties properly
	 *
	 * @param array $configuration em configuration
	 */
	public function __construct(array $configuration) {
		foreach ($configuration as $key => $value) {
			if (property_exists(__CLASS__, $key)) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * @var integer
	 */
	protected $removeListActionFromFlexforms = 2;

	/**
	 * @var string
	 */
	protected $pageModuleFieldsNews = '';

	/**
	 * @var string
	 */
	protected $pageModuleFieldsCategory = '';

	/**
	 * @var integer
	 */
	protected $tagPid = 0;

	/**
	 * @var boolean;
	 */
	protected $prependAtCopy = TRUE;

	/**
	 * @var string;
	 */
	protected $categoryRestriction = '';

	/**
	 * @var boolean
	 */
	protected $contentElementRelation = FALSE;

	/**
	 * @var boolean
	 */
	protected $manualSorting = FALSE;

	/**
	 * @var string
	 */
	protected $archiveDate = 'date';

	/**
	 * @var boolean
	 */
	protected $showImporter = FALSE;

	/**
	 * @var boolean
	 */
	protected $removeInaccessibleCategorySubtrees = TRUE;

	/**
	 * @return integer
	 */
	public function getRemoveListActionFromFlexforms() {
		return (int)$this->removeListActionFromFlexforms;
	}

	/**
	 * @return string
	 */
	public function getPageModuleFieldsNews() {
		return $this->pageModuleFieldsNews;
	}

	/**
	 * @return string
	 */
	public function getPageModuleFieldsCategory() {
		return $this->pageModuleFieldsCategory;
	}

	/**
	 * @return integer
	 */
	public function getTagPid() {
		return (int)$this->tagPid;
	}

	/**
	 *
	 * @return boolean
	 */
	public function getPrependAtCopy() {
		return (boolean)$this->prependAtCopy;
	}

	/**
	 * @return string
	 */
	public function getCategoryRestriction() {
		return $this->categoryRestriction;
	}

	/**
	 * @return boolean
	 */
	public function getContentElementRelation() {
		return (boolean)$this->contentElementRelation;
	}

	/**
	 * @return boolean
	 */
	public function getManualSorting() {
		return (boolean)$this->manualSorting;
	}

	/**
	 * @return string
	 */
	public function getArchiveDate() {
		return $this->archiveDate;
	}

	/**
	 * @return boolean
	 */
	public function getShowImporter() {
		return (boolean)$this->showImporter;
	}

	/**
	 * @return boolean
	 */
	public function getRemoveInaccessibleCategorySubtrees() {
		return $this->removeInaccessibleCategorySubtrees;
	}

}
?>