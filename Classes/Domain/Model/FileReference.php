<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Frans Saris <frans@beech.it>
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
 * File Reference
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Domain_Model_FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference {

	/**
	 * @var string
	 */
	protected $tableLocal = 'sys_file';

	/**
	 * Obsolete when https://review.typo3.org/#/c/21120/
	 * or http://forge.typo3.org/issues/47694 is in
	 * @var string
	 */
	protected $fieldname = 'related_files';

	/**
	 * Obsolete when foreign_selector is supported by ExtBase persistence layer
	 * @var integer
	 */
	protected $uidLocal;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $alternative;

	/**
	 * @var string
	 */
	protected $link;

	/**
	 * @var boolean
	 */
	protected $showinpreview;

	/**
	 * Set File UID
	 *
	 * @param integer $fileUid
	 */
	public function setFileUid($fileUid) {
		$this->uidLocal = $fileUid;
	}

	/**
	 * Get File UID
	 *
	 * @return int
	 */
	public function getFileUid() {
		return $this->uidLocal;
	}

	/**
	 * Set alternative
	 *
	 * @param string $alternative
	 */
	public function setAlternative($alternative) {
		$this->alternative = $alternative;
	}

	/**
	 * Get alternative
	 *
	 * @return string
	 */
	public function getAlternative() {
		return $this->alternative !== NULL ? $this->alternative : $this->getOriginalResource()->getAlternative();
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description !== NULL ? $this->description : $this->getOriginalResource()->getDescription();
	}

	/**
	 * Set link
	 *
	 * @param mixed $link
	 */
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	 * Get link
	 *
	 * @return mixed
	 */
	public function getLink() {
		return $this->link !== NULL ? $this->link : $this->getOriginalResource()->getLink();
	}

	/**
	 * Set title
	 *
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title !== NULL ? $this->title : $this->getOriginalResource()->getTitle();
	}

	/**
	 * Set showinpreview
	 *
	 * @param boolean $showinpreview
	 */
	public function setShowinpreview($showinpreview) {
		$this->showinpreview = $showinpreview;
	}

	/**
	 * Get showinpreview
	 *
	 * @return boolean
	 */
	public function getShowinpreview() {
		return $this->showinpreview;
	}

}