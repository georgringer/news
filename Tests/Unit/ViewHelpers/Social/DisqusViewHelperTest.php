<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Social;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\ViewHelpers\Social\DisqusViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Tests for DisqusSizeViewHelper
 */
class DisqusViewHelperTest extends UnitTestCase
{

    /**
     * Test if default file format works
     *
     * @test
     */
    public function viewHelperReturnsCorrectJs()
    {
        $newsItem = new News();
        $newsItem->setTitle('fobar');

        $language = 'en';

        $viewHelper = new DisqusViewHelper();
        $settingsService = $this->getAccessibleMock('GeorgRinger\\News\\Service\\SettingsService');
        $settingsService->expects($this->any())
            ->method('getSettings')
            ->will($this->returnValue(['disqusLocale' => $language]));

        $viewHelper->injectSettingsService($settingsService);
        $actualResult = $viewHelper->render($newsItem, 'abcdef', 'http://typo3.org/dummy/fobar.html');

        $expectedCode = '<script type="text/javascript">
					var disqus_shortname = ' . GeneralUtility::quoteJSvalue('abcdef', true) . ';
					var disqus_identifier = ' . GeneralUtility::quoteJSvalue('news_' . $newUid, true) . ';
					var disqus_url = ' . GeneralUtility::quoteJSvalue('http://typo3.org/dummy/fobar.html') . ';
					var disqus_title = ' . GeneralUtility::quoteJSvalue('fobar', true) . ';
					var disqus_config = function () {
						this.language = ' . GeneralUtility::quoteJSvalue($language) . ';
					};

					(function() {
						var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
						dsq.src = "//" + disqus_shortname + ".disqus.com/embed.js";
						(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
					})();
				</script>';

        $this->assertEquals($expectedCode, $actualResult);
    }
}
