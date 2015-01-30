<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

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
 * Test for MetaTagViewHelper
 */
class MetaTagViewHelperTest extends \TYPO3\CMS\Fluid\Tests\Unit\ViewHelpers\ViewHelperBaseTestcase {

	/** @var  \TYPO3\CMS\Core\Page\PageRenderer */
	protected $pageRenderer;

	/**
	 * @var \GeorgRinger\News\ViewHelpers\MetaTagViewHelper
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
		$this->viewHelper = $this->getMock($this->buildAccessibleProxy('GeorgRinger\\News\\ViewHelpers\\MetaTagViewHelper'), array('renderChildren'));
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