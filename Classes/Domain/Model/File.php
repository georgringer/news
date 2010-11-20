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
 * File model
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Domain_Model_File extends Tx_Extbase_DomainObject_AbstractValueObject {
	/**
	 * @var integer
	 */
	protected $pid;
	
	/**
	 * @var DateTime
	 */
	protected $crdate;

	/**
	 * @var DateTime
	 */
	protected $tstamp;
	
	/**
	 * @var integer
	 */
	protected $sysLanguageUid;

	/**
	 * @var integer
	 */
	protected $l10nParent;
	
	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $file;

	public function getPid() {
		return $this->pid;
	}

	public function setPid($pid) {
		$this->pid = $pid;
	}

	public function getCrdate() {
		return $this->crdate;
	}

	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	public function getTstamp() {
		return $this->tstamp;
	}

	public function setTstamp($tstamp) {
		$this->tstamp = $tstamp;
	}	
	
	public function getSysLanguageUid() {
		return $this->sysLanguageUid;
	}

	public function setSysLanguageUid($sysLanguageUid) {
		$this->sysLanguageUid = $sysLanguageUid;
	}

	public function getL10nParent() {
		return $this->l10nParent;
	}

	public function setL10nParent($l10nParent) {
		$this->l10nParent = $l10nParent;
	}
	
	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getFile() {
		return $this->file;
	}

	public function setFile($file) {
		$this->file = $file;
	}






}


?>