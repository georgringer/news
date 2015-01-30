<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Social;

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
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\ViewHelpers\Social\DisqusViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Tests for DisqusSizeViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class DisqusViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperReturnsCorrectJs() {
		$newsItem = new News();
		$newsItem->setTitle('fobar');

		$language = 'en';

		$viewHelper = new DisqusViewHelper();
		$settingsService = $this->getAccessibleMock('GeorgRinger\\News\\Service\\SettingsService');
		$settingsService->expects($this->any())
			->method('getSettings')
			->will($this->returnValue(array('disqusLocale' => $language)));

		$viewHelper->injectSettingsService($settingsService);
		$actualResult = $viewHelper->render($newsItem, 'abcdef', 'http://typo3.org/dummy/fobar.html');

		$expectedCode = '<script type="text/javascript">
					var disqus_shortname = ' . GeneralUtility::quoteJSvalue('abcdef', TRUE) . ';
					var disqus_identifier = ' . GeneralUtility::quoteJSvalue('news_' . $newUid, TRUE) . ';
					var disqus_url = ' . GeneralUtility::quoteJSvalue('http://typo3.org/dummy/fobar.html') . ';
					var disqus_title = ' . GeneralUtility::quoteJSvalue('fobar', TRUE) . ';
					var disqus_config = function () {
						this.language = ' . GeneralUtility::quoteJSvalue($language) . ';
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