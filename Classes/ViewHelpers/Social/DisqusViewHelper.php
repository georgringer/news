<?php

namespace GeorgRinger\News\ViewHelpers\Social;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to add disqus thread
 * Details: http://www.disqus.com/
 * Example
 * ==============
 * <div id="disqus_thread"></div>
 * <n:social.disqus newsItem="{newsItem}"
 *         shortName="demo123"
 *         link="{n:link(newsItem:newsItem,settings:settings,uriOnly:1,configuration:'{forceAbsoluteUrl:1}')}" />
 *
 */
class DisqusViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * @var bool
     */
    protected $escapingInterceptorEnabled = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @var \GeorgRinger\News\Service\SettingsService
     */
    protected $pluginSettingsService;

    /**
     * @var \GeorgRinger\News\Service\SettingsService $pluginSettingsService
     */
    public function injectSettingsService(\GeorgRinger\News\Service\SettingsService $pluginSettingsService)
    {
        $this->pluginSettingsService = $pluginSettingsService;
    }

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('newsItem', News::class, 'news item', true);
        $this->registerArgument('shortName', 'string', 'short name', true);
        $this->registerArgument('link', 'string', 'link', true);
    }

    /**
     * Render disqus thread
     *
     * @param \GeorgRinger\News\Domain\Model\News $newsItem news item
     * @param string $shortName shortname
     * @param string $link link
     * @return string
     */
    public function render()
    {
        $tsSettings = $this->pluginSettingsService->getSettings();
        $newsItem = $this->arguments['newsItem'];

        $code = '<script type="text/javascript">
					var disqus_shortname = ' . GeneralUtility::quoteJSvalue($this->arguments['shortName'], true) . ';
					var disqus_identifier = \'news_' . $newsItem->getUid() . '\';
					var disqus_url = ' . GeneralUtility::quoteJSvalue($this->arguments['link'], true) . ';
					var disqus_title = ' . GeneralUtility::quoteJSvalue($newsItem->getTitle(), true) . ';
					var disqus_config = function () {
						this.language = ' . GeneralUtility::quoteJSvalue($tsSettings['disqusLocale']) . ';
					};

					(function() {
						var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
						dsq.src = "//" + disqus_shortname + ".disqus.com/embed.js";
						(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
					})();
				</script>';

        return $code;
    }
}
