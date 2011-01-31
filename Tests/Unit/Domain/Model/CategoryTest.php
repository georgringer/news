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
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News2_Tests_Unit_Domain_Model_CategoryTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_News2_Domain_Model_Category
	 */
	protected $categoryDomainModelInstance;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->categoryDomainModelInstance = $this->objectManager->get('Tx_News2_Domain_Model_Category');
	}

	/**
	 * @test
	 * @return void
	 */
	public function titleCanBeSet() {
		$title = 'Category title';
		$this->categoryDomainModelInstance->setTitle($title);
		$this->assertEquals($title, $this->categoryDomainModelInstance->getTitle());
	}

	/**
	 * @test
	 * @return void
	 */
	public function descriptionCanBeSet() {
		$description = 'Category description';
		$this->categoryDomainModelInstance->setDescription($description);
		$this->assertEquals($description, $this->categoryDomainModelInstance->getDescription());
	}

	/**
	 * @test
	 * @return void
	 */
	public function imageCanBeSet() {
		$image = 'categoryImage.jpg';
		$this->categoryDomainModelInstance->setImage($image);
		$this->assertEquals($image, $this->categoryDomainModelInstance->getImage());
	}

	/**
	 * @test
	 * @return void
	 */
	public function shortcutCanBeSet() {
		$shortcut = 'category shortcut';
		$this->categoryDomainModelInstance->setShortcut($shortcut);
		$this->assertEquals($shortcut, $this->categoryDomainModelInstance->getShortcut());
	}

	/**
	 * @test
	 * @return void
	 */
	public function singlePidCanBeSet() {
		$singlePid = 4711;
		$this->categoryDomainModelInstance->setSinglePid($singlePid);
		$this->assertEquals($singlePid, $this->categoryDomainModelInstance->getSinglePid());
	}

	/**
	 * @test
	 * @return void
	 */
	public function feGroupCanBeSet() {
		$feGroup = 666;
		$this->categoryDomainModelInstance->setFeGroup($feGroup);
		$this->assertEquals($feGroup, $this->categoryDomainModelInstance->getFeGroup());
	}

	/**
	 * @test
	 * @return void
	 */
	public function importIdCanBeSet() {
		$importId = 2;
		$this->categoryDomainModelInstance->setImportId($importId);
		$this->assertEquals($importId, $this->categoryDomainModelInstance->getImportId());
	}

	/**
	 * @test
	 * @return void
	 */
	public function sortingCanBeSet() {
		$sorting = 2;
		$this->categoryDomainModelInstance->setSorting($sorting);
		$this->assertEquals($sorting, $this->categoryDomainModelInstance->getSorting());
	}
}
?>