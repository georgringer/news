<?php
namespace GeorgRinger\News\Domain\Model\Dto;

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

/**
 * Extension Manager configuration
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class EmConfiguration {

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
	 * @var bool
	 */
	protected $categoryBeGroupTceFormsRestriction = FALSE;

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

	/** @var boolean */
	protected $rteForTeaser = FALSE;

	/**
	 * @var boolean
	 */
	protected $showAdministrationModule = TRUE;

	/**
	 * @var boolean
	 */
	protected $showMediaDescriptionField = FALSE;

	/**
	 * @var int
	 */
	protected $useFal;

	/**
	 * @var int
	 */
	protected $storageUidImporter = 1;

	/**
	 * @var string
	 */
	protected $resourceFolderImporter = '/news_import';

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
	 * Get categoryBeGroupTceFormsRestriction
	 *
	 * @return boolean
	 */
	public function getCategoryBeGroupTceFormsRestriction() {
		return $this->categoryBeGroupTceFormsRestriction;
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
	 * @param boolean $showAdministrationModule
	 * @return void
	 */
	public function setShowAdministrationModule($showAdministrationModule) {
		$this->showAdministrationModule = $showAdministrationModule;
	}

	/**
	 * @return boolean
	 */
	public function getShowAdministrationModule() {
		return $this->showAdministrationModule;
	}

	/**
	 * @param boolean $showMediaDescriptionField
	 * @return void
	 */
	public function setShowMediaDescriptionField($showMediaDescriptionField) {
		$this->showMediaDescriptionField = $showMediaDescriptionField;
	}

	/**
	 * @return boolean
	 */
	public function getShowMediaDescriptionField() {
		return $this->showMediaDescriptionField;
	}

	/**
	 * @return boolean
	 */
	public function getRteForTeaser() {
		return $this->rteForTeaser;
	}

	/**
	 * @return int
	 */
	public function getUseFal() {
		return version_compare(TYPO3_branch, '6.0', '>=') ? (int) $this->useFal : 0;
	}

	/**
	 * @return string
	 */
	public function getResourceFolderImporter() {
		return $this->resourceFolderImporter;
	}

	/**
	 * @return int
	 */
	public function getStorageUidImporter() {
		return $this->storageUidImporter;
	}
}
