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
 * Test for Tx_News_ViewHelpers_MetaTagViewHelper
 */
class Tx_News_Tests_Unit_ViewHelpers_MetaTagViewHelperTest extends \TYPO3\CMS\Fluid\Tests\Unit\ViewHelpers\ViewHelperBaseTestcase {

	/** @var  \TYPO3\CMS\Core\Page\PageRenderer */
	protected $pageRenderer;

	/**
	 * @var Tx_News_ViewHelpers_MetaTagViewHelper
	 */
	protected $viewHelper;

	/**
	 * Set up
	 */
	public function setUp() {
		parent::setUp();

		$this->pageRenderer = $this->getAccessibleMock('TYPO3\\CMS\\Core\\Page\\PageRenderer', array('dummy'), array(), '', FALSE);

		$GLOBALS['TSFE'] = $this->getAccessibleMock('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', array('getPageRenderer'), array(), '', FALSE);
		$GLOBALS['TSFE']->_set('pageRenderer', $this->pageRenderer);
		$GLOBALS['TSFE']->expects($this->any())->method('getPageRenderer')->will($this->returnValue($this->pageRenderer));
		$this->viewHelper = $this->getMock($this->buildAccessibleProxy('Tx_News_ViewHelpers_MetaTagViewHelper'), array('renderChildren'));
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 */
	public function metaTagWithCustomPropertyIsSet() {
		$mockTagBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Fluid\\Core\\ViewHelper\\TagBuilder', array('dummy'));
		$this->viewHelper->_set('arguments', array(
			'content' => 'fo',
			'property' => 'bar',
		));
		$this->viewHelper->_set('tag', $mockTagBuilder);
		$this->viewHelper->initialize();
		$this->viewHelper->render();
		$this->assertEquals($this->pageRenderer->_get('metaTags'), array('<meta property="bar" content="fo" />'));
	}

	/**
	 * @test
	 */
	public function nameAttributeCanBeUsed() {
		$mockTagBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Fluid\\Core\\ViewHelper\\TagBuilder', array('dummy'));
		$this->viewHelper->_set('arguments', array(
			'content' => 'ipsum',
			'name' => 'lorem',
		));
		$this->viewHelper->_set('tag', $mockTagBuilder);
		$this->viewHelper->initialize();
		$this->viewHelper->render();
		$this->assertEquals($this->pageRenderer->_get('metaTags'), array('<meta name="lorem" content="ipsum" />'));
	}

	/**
	 * @test
	 */
	public function metaTagWithNoContentIsIgnoredPropertyIsSet() {
		$mockTagBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Fluid\\Core\\ViewHelper\\TagBuilder', array('dummy'));
		$this->viewHelper->_set('arguments', array(
			'property' => 'title',
		));
		$this->viewHelper->_set('tag', $mockTagBuilder);
		$this->viewHelper->initialize();
		$this->viewHelper->render();
		$this->assertEquals($this->pageRenderer->_get('metaTags'), array());
	}

	/**
	 * @test
	 */
	public function currentDomainIsSetToPropertyContent() {
		$mockTagBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Fluid\\Core\\ViewHelper\\TagBuilder', array('dummy'));
		$this->viewHelper->_set('arguments', array(
			'property' => 'title',
		));
		$this->viewHelper->_set('tag', $mockTagBuilder);
		$this->viewHelper->initialize();
		$this->viewHelper->render(TRUE);
		$this->assertEquals($this->pageRenderer->_get('metaTags'), array('<meta property="title" content="' . \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL') . '" />'));
	}

	/**
	 * @test
	 */
	public function absoluteUrlCanBeForced() {
		$mockTagBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Fluid\\Core\\ViewHelper\\TagBuilder', array('dummy'));
		$this->viewHelper->_set('arguments', array(
			'property' => 'title',
		));
		$this->viewHelper->_set('tag', $mockTagBuilder);
		$this->viewHelper->initialize();
		$this->viewHelper->render(TRUE, TRUE);
		$this->assertEquals($this->pageRenderer->_get('metaTags'), array('<meta property="title" content="' . \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . '" />'));
	}
}