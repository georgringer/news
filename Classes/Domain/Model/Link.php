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
 * Link model
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Link extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {

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
	protected $uri;

	/**
	 * @var integer
	 */
	protected $l10nParent;

	/**
	 * Get creation date
	 *
	 * @return integer
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Set creation date
	 *
	 * @param integer $crdate creation date
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Get timestamp
	 *
	 * @return integer
	 */
	public function getTstamp() {
		return $this->tstamp;
	}

	/**
	 * Set timestamp
	 *
	 * @param integer $tstamp timestamp
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
	 * Get uri
	 *
	 * @return string
	 */
	public function getUri() {
		return $this->uri;
	}

	/**
	 * Set uri
	 *
	 * @param string $uri uri
	 * @return void
	 */
	public function setUri($uri) {
		$this->uri = $uri;
	}

	/**
	 * Set sys language
	 *
	 * @param int $sysLanguageUid
	 * @return void
	 */
	public function setSysLanguageUid($sysLanguageUid) {
		$this->_languageUid = $sysLanguageUid;
	}

	/**
	 * Get sys language
	 *
	 * @return int
	 */
	public function getSysLanguageUid() {
		return $this->_languageUid;
	}

	/**
	 * Set l10n parent
	 *
	 * @param int $l10nParent
	 * @return void
	 */
	public function setL10nParent($l10nParent) {
		$this->l10nParent = $l10nParent;
	}

	/**
	 * Get l10n parent
	 *
	 * @return int
	 */
	public function getL10nParent() {
		return $this->l10nParent;
	}

}
