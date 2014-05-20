<?php

namespace GeorgRinger\News\Tests\Unit\Functional\ViewHelpers;

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


use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Functional test for the Tx_News_ViewHelpers_SimplePrevNextViewHelper
 */
class SimplePrevNextViewHelperTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase {

	/** @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface|\Tx_News_ViewHelpers_SimplePrevNextViewHelper */
	protected $mockedViewHelper;

	/** @var \Tx_News_Domain_Model_News */
	protected $news;

	protected $testExtensionsToLoad = array('typo3conf/ext/news');
	protected $coreExtensionsToLoad = array('extbase', 'fluid');

	public function setUp() {
		parent::setUp();
		$this->mockedViewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_SimplePrevNextViewHelper', array('dummy'), array(), '', TRUE, TRUE, FALSE);

		$this->news = new \Tx_News_Domain_Model_News();
		$this->news->setPid(9);

		$this->importDataSet(__DIR__ . '/../Fixtures/tx_news_domain_model_news.xml');
	}

	/**
	 * @test
	 * @return void
	 */
	public function allNeighboursCanBeFound() {
		$this->news->_setProperty('uid', 103);

		$fo = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');

		$exp = array(
			0 => array(
				'uid' => 102,
				'title' => NULL
			),
			1 => array(
				'uid' => 103,
				'title' => NULL
			),
			2 => array(
				'uid' => 104,
				'title' => NULL
			)
		);
		$this->assertEquals($exp, $fo);
	}


	/**
	 * @test
	 * @return void
	 */
	public function nextNeighbourCanBeFound() {
		$this->news->_setProperty('uid', 101);

		$fo = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');

		$exp = array(
			0 => array(
				'uid' => 102,
				'title' => NULL
			),
		);
		$this->assertEquals($exp, $fo);
	}

	/**
	 * @test
	 * @return void
	 */
	public function previousNeighbourCanBeFound() {
		$this->news->_setProperty('uid', 106);

		$fo = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');

		$exp = array(
			0 => array(
				'uid' => 105,
				'title' => NULL
			),
			1 => array(
				'uid' => 106,
				'title' => NULL
			),
		);
		$this->assertEquals($exp, $fo);
	}

}