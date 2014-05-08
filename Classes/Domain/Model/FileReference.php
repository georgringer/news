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
	 * Obsolete when foreign_selector is supported by ExtBase persistence layer
	 *
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
	 * Set File uid
	 *
	 * @param integer $fileUid
	 * @return void
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
	 * @return void
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
	 * @return void
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
	 * @param string $link
	 * @return void
	 */
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	 * Get link
	 *
	 * @return mixed
	 * @return void
	 */
	public function getLink() {
		return $this->link !== NULL ? $this->link : $this->getOriginalResource()->getLink();
	}

	/**
	 * Set title
	 *
	 * @param string $title
	 * @return void
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
	 * @return void
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