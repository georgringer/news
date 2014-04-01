<?php

namespace GeorgRinger\News\Tests\Unit\Functional\Repository;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Georg Ringer <typo3@ringerge.org>
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Functional test for the DataHandler
 */
class CategoryRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase {

	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/** @var  \Tx_News_Domain_Repository_CategoryRepository */
	protected $categoryRepository;

	protected $testExtensionsToLoad = array('typo3conf/ext/news');

	public function setUp() {
		parent::setUp();
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->categoryRepository = $this->objectManager->get('Tx_News_Domain_Repository_NewsRepository');

		$this->importDataSet(__DIR__ . '/../Fixtures/tx_news_domain_model_category.xml');
	}


	/**
	 * Test if by import source is done
	 *
	 * @test
	 * @return void
	 */
	public function findRecordByImportSource() {
		$category = $this->categoryRepository->findOneByImportSourceAndImportId('functional_test', '2');

		$this->assertEquals($category->getTitle(), 'findRecordByImportSource');
	}

}