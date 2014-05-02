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
 * Tests for DisqusSizeViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_ViewHelpers_Social_DisqusViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/**
	 * Set up test class
	 *
	 * @return void
	 */
	public function setUp() {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
	}

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperReturnsCorrectJs() {
		$newsRepository = $this->objectManager->get('Tx_News_Domain_Repository_NewsRepository');

		$newsItem = new Tx_News_Domain_Model_News();
		$newsItem->setTitle('fobar');

		$language = 'en';

		$viewHelper = new Tx_News_ViewHelpers_Social_DisqusViewHelper();
		$settingsService = $this->getAccessibleMock('Tx_News_Service_SettingsService');
		$settingsService->expects($this->any())
			->method('getSettings')
			->will($this->returnValue(array('disqusLocale' => $language)));

		$viewHelper->injectSettingsService($settingsService);
		$actualResult = $viewHelper->render($newsItem, 'abcdef', 'http://typo3.org/dummy/fobar.html');

		$expectedCode = '<script type="text/javascript">
					var disqus_shortname = ' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue('abcdef', TRUE) . ';
					var disqus_identifier = ' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue('news_' . $newUid, TRUE) . ';
					var disqus_url = ' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue('http://typo3.org/dummy/fobar.html') . ';
					var disqus_title = ' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue('fobar', TRUE) . ';
					var disqus_config = function () {
						this.language = ' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue($language) . ';
					};

					(function() {
						var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
						dsq.src = "http://" + disqus_shortname + ".disqus.com/embed.js";
						(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
					})();
				</script>';

		$this->assertEquals($expectedCode, $actualResult);
	}

}