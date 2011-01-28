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
 * Tests for domains model News
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 * @author Georg Ringer <typo3@ringerge.org>
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News2_Tests_Unit_Domain_Model_NewsTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_News2_Domain_Model_News
	 */
	protected $newsDomainModelInstance;

	protected function setUp() {
		$this->newsDomainModelInstance = $this->objectManager->get('Tx_News2_Domain_Model_News');
	}

	/**
	 * @test
	 */
	public function titleCanBeSet() {
		$title = 'News title';
		$this->newsDomainModelInstance->setTitle($title);
		$this->assertEquals($title, $this->newsDomainModelInstance->getTitle());
	}

	/**
	 * @test
	 */
	public function teaserCanBeSet() {
		$teaser = 'News teaser';
		$this->newsDomainModelInstance->setTeaser($teaser);
		$this->assertEquals($teaser, $this->newsDomainModelInstance->getTeaser());
	}

	/**
	 * @test
	 */
	public function bodytextCanBeSet() {
		$bodytext = 'News bodytext';
		$this->newsDomainModelInstance->setBodytext($bodytext);
		$this->assertEquals($bodytext, $this->newsDomainModelInstance->getBodytext());
	}

	/**
	 * @test
	 */
	public function datetimeCanBeSet() {
		$datetime = new DateTime();
		$this->newsDomainModelInstance->setDatetime($datetime);
		$this->assertEquals($datetime, $this->newsDomainModelInstance->getDatetime());
	}

	/**
	 * @test
	 */
	public function archiveCanBeSet() {
		$archive = new DateTime();
		$this->newsDomainModelInstance->setArchive($archive);
		$this->assertEquals($archive, $this->newsDomainModelInstance->getArchive());
	}

	/**
	 * @test
	 */
	public function authorCanBeSet() {
		$author = 'News author';
		$this->newsDomainModelInstance->setAuthor($author);
		$this->assertEquals($author, $this->newsDomainModelInstance->getAuthor());
	}

	/**
	 * @test
	 */
	public function authorEmailCanBeSet() {
		$authorEmail = 'author@news.org';
		$this->newsDomainModelInstance->setAuthorEmail($authorEmail);
		$this->assertEquals($authorEmail, $this->newsDomainModelInstance->getAuthorEmail());
	}

	/**
	 * @test
	 */
	public function typeCanBeSet() {
		$type = 123;
		$this->newsDomainModelInstance->setType($type);
		$this->assertEquals($type, $this->newsDomainModelInstance->getType());
	}

	/**
	 * @test
	 */
	public function keywordsCanBeSet() {
		$keywords = 'news1 keyword, news2 keyword';
		$this->newsDomainModelInstance->setKeywords($keywords);
		$this->assertEquals($keywords, $this->newsDomainModelInstance->getKeywords());
	}

	/**
	 * @test
	 */
	public function internalurlCanBeSet() {
		$internalurl = 'http://foo.org/';
		$this->newsDomainModelInstance->setInternalurl($internalurl);
		$this->assertEquals($internalurl, $this->newsDomainModelInstance->getInternalurl());
	}

	/**
	 * @test
	 */
	public function externalurlCanBeSet() {
		$externalurl = 'http://bar.org/';
		$this->newsDomainModelInstance->setExternalurl($externalurl);
		$this->assertEquals($externalurl, $this->newsDomainModelInstance->getExternalurl());
	}

	/**
	 * @test
	 */
	public function isttopnewsCanBeSet() {
		$istopnews = TRUE;
		$this->newsDomainModelInstance->setIstopnews($istopnews);
		$this->assertEquals($istopnews, $this->newsDomainModelInstance->getIstopnews());
	}

	/**
	 * @test
	 */
	public function editlockCanBeSet() {
		$editlock = 2;
		$this->newsDomainModelInstance->setEditlock($editlock);
		$this->assertEquals($editlock, $this->newsDomainModelInstance->getEditlock());
	}

	/**
	 * @test
	 */
	public function importIdCanBeSet() {
		$importId = 2;
		$this->newsDomainModelInstance->setImportId($importId);
		$this->assertEquals($importId, $this->newsDomainModelInstance->getImportId());
	}

	/**
	 * @test
	 */
	public function sortingCanBeSet() {
		$sorting = 2;
		$this->newsDomainModelInstance->setSorting($sorting);
		$this->assertEquals($sorting, $this->newsDomainModelInstance->getSorting());
	}
}
?>