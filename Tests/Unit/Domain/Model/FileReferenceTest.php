<?php
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
 * Tests for Tx_News_Domain_Model_FileReference
 */
class Tx_News_Tests_Unit_Domain_Model_FileReferenceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if fileUid can be set
	 *
	 * @test
	 * @return void
	 */
	public function fileUidCanBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_FileReference();
		$value = 'Test 123';
		$domainModelInstance->setFileUid($value);
		$this->assertEquals($value, $domainModelInstance->getFileUid());
	}

	/**
	 * Test if alternative can be set
	 *
	 * @test
	 * @return void
	 */
	public function alternativeBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_FileReference();
		$value = 'Test 123';
		$domainModelInstance->setAlternative($value);
		$this->assertEquals($value, $domainModelInstance->getAlternative());
	}

	/**
	 * Test if description can be set
	 *
	 * @test
	 * @return void
	 */
	public function descriptionBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_FileReference();
		$value = 'Test 123';
		$domainModelInstance->setDescription($value);
		$this->assertEquals($value, $domainModelInstance->getDescription());
	}

	/**
	 * Test if link can be set
	 *
	 * @test
	 * @return void
	 */
	public function linkBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_FileReference();
		$value = 'Test 123';
		$domainModelInstance->setLink($value);
		$this->assertEquals($value, $domainModelInstance->getLink());
	}

	/**
	 * Test if title can be set
	 *
	 * @test
	 * @return void
	 */
	public function titleBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_FileReference();
		$value = 'Test 123';
		$domainModelInstance->setTitle($value);
		$this->assertEquals($value, $domainModelInstance->getTitle());
	}

	/**
	 * Test if showInPreview can be set
	 *
	 * @test
	 * @return void
	 */
	public function showInPreviewBeSet() {
		$domainModelInstance = new Tx_News_Domain_Model_FileReference();
		$value = TRUE;
		$domainModelInstance->setShowinpreview($value);
		$this->assertEquals($value, $domainModelInstance->getShowinpreview());
	}

}
