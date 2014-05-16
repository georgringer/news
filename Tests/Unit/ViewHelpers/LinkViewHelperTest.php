<?php
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


/**
 * Test for Tx_News_ViewHelpers_LinkViewHelper
 */
class Tx_News_Tests_Unit_ViewHelpers_LinkViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	protected $mockedContentObjectRenderer;

	protected $mockedViewHelper;

	/** @var Tx_News_Domain_Model_News */
	protected $newsItem;

	/**
	 * Set up
	 */
	public function setUp() {
		$this->mockedViewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_LinkViewHelper', array('init', 'renderChildren'));
		$this->mockedContentObjectRenderer = $this->getAccessibleMock('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer', array('typoLink_URL'));
		$pluginSettings = $this->getAccessibleMock('Tx_News_Service_SettingsService', array('getSettings'));
		$tag = $this->getAccessibleMock('TYPO3\\CMS\\Fluid\\Core\\ViewHelper\\TagBuilder', array('addAttribute', 'setContent', 'render'));
		$this->mockedViewHelper->_set('cObj', $this->mockedContentObjectRenderer);
		$this->mockedViewHelper->_set('tag', $tag);
		$this->mockedViewHelper->_set('pluginSettingsService', $pluginSettings);

		$this->newsItem = new Tx_News_Domain_Model_News();
	}

	/**
	 * @test
	 */
	public function internalPageIsUsed() {
		$url = '123';
		$result = array('parameter' => $url);

		$this->mockedContentObjectRenderer->expects($this->once())->method('typoLink_URL')->with($result);

		$this->newsItem->setType(1);
		$this->newsItem->setInternalurl($url);

		$this->mockedViewHelper->render($this->newsItem);
	}

	/**
	 * @test
	 */
	public function externalUrlIsUsed() {
		$url = 'http://www.typo3.org';
		$result = array('parameter' => $url);

		$this->mockedContentObjectRenderer->expects($this->once())->method('typoLink_URL')->with($result);

		$this->newsItem->setType(2);
		$this->newsItem->setExternalurl($url);

		$this->mockedViewHelper->render($this->newsItem);
	}

	/**
	 * @test
	 * @return void
	 */
	public function humanReadAbleDateIsAddedToConfiguration() {
		$dateTime = new DateTime('2014-05-16');
		$newsItem = new Tx_News_Domain_Model_News();
		$newsItem->_setProperty('uid', 123);
		$newsItem->setDatetime($dateTime);

		$tsSettings = array(
			'link' => array(
				'hrDate' => array(
					'_typoScriptNodeValue' => 1,
					'day' => 'j',
					'month' => 'n',
					'year' => 'Y',
				)
			)
		);
		$configuration = array();
		$expected = '&tx_news_pi1[news]=123&tx_news_pi1[controller]=News&tx_news_pi1[action]=detail&tx_news_pi1[day]=16&tx_news_pi1[month]=5&tx_news_pi1[year]=2014';

		$result = $this->mockedViewHelper->_call('getLinkToNewsItem', $newsItem, $tsSettings, $configuration);
		$this->assertEquals($expected, $result['additionalParams']);
	}

	/**
	 * @test
	 * @return void
	 */
	public function controllerAndActionAreSkippedInUrl() {
		$newsItem = new Tx_News_Domain_Model_News();
		$newsItem->_setProperty('uid', 123);

		$tsSettings = array(
			'link' => array(
				'skipControllerAndAction' => 1
			)
		);
		$configuration = array();
		$expected = '&tx_news_pi1[news]=123';

		$result = $this->mockedViewHelper->_call('getLinkToNewsItem', $newsItem, $tsSettings, $configuration);
		$this->assertEquals($expected, $result['additionalParams']);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getDetailPidFromCategoriesReturnsCorrectValue() {
		$viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_LinkViewHelper', array('dummy'));

		$newsItem = new Tx_News_Domain_Model_News();

		$categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$category1 = new Tx_News_Domain_Model_Category();
		$categories->attach($category1);

		$category2 = new Tx_News_Domain_Model_Category();
		$category2->setSinglePid('123');
		$categories->attach($category2);

		$category3 = new Tx_News_Domain_Model_Category();
		$category3->setSinglePid('456');
		$categories->attach($category3);

		$newsItem->setCategories($categories);

		$result = $viewHelper->_call('getDetailPidFromCategories', array(), $newsItem);
		$this->assertEquals(123, $result);
	}

	/**
	 * @test
	 * @dataProvider getDetailPidFromDefaultDetailPidReturnsCorrectValueDataProvider
	 * @return void
	 */
	public function getDetailPidFromDefaultDetailPidReturnsCorrectValue($settings, $expected) {
		$viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_LinkViewHelper', array('dummy'));

		$result = $viewHelper->_call('getDetailPidFromDefaultDetailPid', $settings, NULL);
		$this->assertEquals($expected, $result);
	}

	public function getDetailPidFromDefaultDetailPidReturnsCorrectValueDataProvider() {
		return array(
			array(NULL, 0),
			array(array(), 0),
			array(array('defaultDetailPid' => '789'), 789),
			array(array('defaultDetailPid' => '45xy'), 45),
		);
	}

	/**
	 * @test
	 * @dataProvider getDetailPidFromFlexformReturnsCorrectValueDataProvider
	 * @return void
	 */
	public function getDetailPidFromFlexformReturnsCorrectValue($settings, $expected) {
		$viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_LinkViewHelper', array('dummy'));

		$result = $viewHelper->_call('getDetailPidFromFlexform', $settings, NULL);
		$this->assertEquals($expected, $result);
	}

	public function getDetailPidFromFlexformReturnsCorrectValueDataProvider() {
		return array(
			array(NULL, 0),
			array(array(), 0),
			array(array('detailPid' => '123'), 123),
			array(array('detailPid' => '456xy'), 456),
		);
	}
}