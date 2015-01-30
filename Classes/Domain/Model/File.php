<?php
namespace GeorgRinger\News\Domain\Model;

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
 * File model
 *
 * @package TYPO3
 * @subpackage tx_news
 * @version $Id$
 */
class File extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {

	/**
	 * @var \DateTime
	 */
	protected $crdate;

	/**
	 * @var \DateTime
	 */
	protected $tstamp;

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
	protected $file;

	/**
	 * Get crdate
	 *
	 * @return \DateTime
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Set crdate
	 *
	 * @param \DateTime $crdate crdate
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Get Tstamp
	 *
	 * @return \DateTime
	 */
	public function getTstamp() {
		return $this->tstamp;
	}

	/**
	 * Set tstamp
	 *
	 * @param \DateTime $tstamp tstamp
	 * @return void
	 */
	public function setTstamp($tstamp) {
		$this->tstamp = $tstamp;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set title
	 *
	 * @param string $title title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Set description
	 *
	 * @param string $description description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Get file
	 *
	 * @return string
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Set File
	 *
	 * @param string $file file
	 * @return void
	 */
	public function setFile($file) {
		$this->file = $file;
	}

	/**
	 * Get file extension
	 *
	 * @return string
	 */
	public function getFileExtension() {
		return pathinfo($this->file, PATHINFO_EXTENSION);
	}

	/**
	 * Get boolean if file ending is registered as image type
	 *
	 * @return boolean
	 */
	public function getIsImageFile() {
		$fileEndings = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'], TRUE);
		return in_array($this->getFileExtension(), $fileEndings);
	}

}
