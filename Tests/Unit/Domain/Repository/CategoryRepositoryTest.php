<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Tests for domain repository categoryRepository
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Tests_Unit_Domain_Repository_CategoryRepositoryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/**
	 * @var array
	 */
	private $backupGlobalVariables;

	public function setUp() {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->backupGlobalVariables = array(
			'BE_USER' => $GLOBALS['BE_USER'],
			'TSFE' => $GLOBALS['TSFE'],
		);
	}

	public function tearDown() {
		foreach ($this->backupGlobalVariables as $key => $data) {
			$GLOBALS[$key] = $data;
		}
		unset($this->backupGlobalVariables);
	}

	/**
	 * @test
	 * @return void
	 */
	public function correctSysLanguageIsReturnedUsingTsfe() {
		/** @var Tx_News_Domain_Repository_CategoryRepository $mockTemplateParser */
		if (version_compare(TYPO3_branch, '6.2', '>=')) {
			$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface');
			$mockTemplateParser = $this->getAccessibleMock('Tx_News_Domain_Repository_CategoryRepository', array('dummy'), array($objectManager));
		} else {
			$mockTemplateParser = $this->getAccessibleMock('Tx_News_Domain_Repository_CategoryRepository', array('dummy'));
		}
		$result = $mockTemplateParser->_call('getSysLanguageUid');

		// Default value
		$this->assertEquals(0, $result);

		if (!is_object($GLOBALS['TSFE'])) {
			$vars = array();
			$type = 1;
			$GLOBALS['TSFE'] = new \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController($vars, 123, $type);
		}
		$GLOBALS['TSFE']->sys_language_content = 9;

		$result = $mockTemplateParser->_call('getSysLanguageUid');
		$this->assertEquals(9, $result);
	}

	/**
	 * @test
	 * @return void
	 */
	public function correctSysLanguageIsReturnedUsingGetAndPostRequest() {
		/** @var Tx_News_Domain_Repository_CategoryRepository $mockedCategoryRepository */
		if (version_compare(TYPO3_branch, '6.2', '>=')) {
			$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface');
			$mockedCategoryRepository = $this->getAccessibleMock('Tx_News_Domain_Repository_CategoryRepository', array('dummy'), array($objectManager));
		} else {
			$mockedCategoryRepository = $this->getAccessibleMock('Tx_News_Domain_Repository_CategoryRepository', array('dummy'));
		}
		unset($GLOBALS['TSFE']);

		// Default value
		$result = $mockedCategoryRepository->_call('getSysLanguageUid');
		$this->assertEquals(0, $result);

		// GET
		$_GET['L'] = 11;
		$result = $mockedCategoryRepository->_call('getSysLanguageUid');
		$this->assertEquals(11, $result);

		// POST
		$_POST['L'] = 13;
		$result = $mockedCategoryRepository->_call('getSysLanguageUid');
		$this->assertEquals(13, $result);

		// POST overrules GET
		$_GET['L'] = 15;
		$this->assertEquals(13, $result);
	}


	/**
	 * Test if category ids are replaced
	 *
	 * @param array $expectedResult
	 * @param array $given
	 * @test
	 * @dataProvider categoryIdsAreCorrectlyReplacedDataProvider
	 * @return void
	 */
	public function categoryIdsAreCorrectlyReplaced($expectedResult, $given) {
		if (version_compare(TYPO3_branch, '6.2', '>=')) {
			$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface');
			$mockTemplateParser = $this->getAccessibleMock('Tx_News_Domain_Repository_CategoryRepository', array('dummy'), array($objectManager));
		} else {
			$mockTemplateParser = $this->getAccessibleMock('Tx_News_Domain_Repository_CategoryRepository', array('dummy'));
		}

		$result = $mockTemplateParser->_call('replaceCategoryIds', $given['idList'], $given['toBeChanged']);

		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function categoryIdsAreCorrectlyReplacedDataProvider() {
		return array(
			'emptyRows' => array(
				array(1, 2, 3),
				array(
					'idList' => array(1, 2, 3),
					'toBeChanged' => array()
				)
			),
			'oneIdReplaced' => array(
				array(1, 5, 3),
				array(
					'idList' => array(1, 2, 3),
					'toBeChanged' => array(
						0 => array(
							'l10n_parent' => 2,
							'uid' => 5,
						)
					)
				)
			),
			'noIdReplaced' => array(
				array(1, 2, 3),
				array(
					'idList' => array(1, 2, 3),
					'toBeChanged' => array(
						0 => array(
							'l10n_parent' => 6,
							'uid' => 5,
						),
						1 => array(
							'l10n_parent' => 8,
							'uid' => 10,
						)
					)
				)
			),
			'allIdsReplaced' => array(
				array(4, 5, 6),
				array(
					'idList' => array(1, 2, 3),
					'toBeChanged' => array(
						array(
							'l10n_parent' => 2,
							'uid' => 5,
						),
						array(
							'l10n_parent' => 1,
							'uid' => 4,
						),
						array(
							'l10n_parent' => 3,
							'uid' => 6,
						),
						array(
							'l10n_parent' => 8,
							'uid' => 10,
						)
					)
				)
			),
		);
	}

}
