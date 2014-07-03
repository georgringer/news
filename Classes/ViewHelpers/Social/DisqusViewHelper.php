<?php
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
 * ViewHelper to add disqus thread
 * Details: http://www.disqus.com/
 * Example
 * ==============
 * <div id="disqus_thread"></div>
 * <n:social.disqus newsItem="{newsItem}"
 *         shortName="demo123"
 *         link="{n:link(newsItem:newsItem,settings:settings,uriOnly:1,configuration:'{forceAbsoluteUrl:1}')}" />
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Social_DisqusViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	protected $escapingInterceptorEnabled = FALSE;

	/**
	 * @var Tx_News_Service_SettingsService
	 */
	protected $pluginSettingsService;

	/**
	 * @var Tx_News_Service_SettingsService $pluginSettingsService
	 * @return void
	 */
	public function injectSettingsService(Tx_News_Service_SettingsService $pluginSettingsService) {
		$this->pluginSettingsService = $pluginSettingsService;
	}

	/**
	 * Render disqus thread
	 *
	 * @param Tx_News_Domain_Model_News $newsItem news item
	 * @param string $shortName shortname
	 * @param string $link link
	 * @return string
	 */
	public function render(Tx_News_Domain_Model_News $newsItem, $shortName, $link) {
		$tsSettings = $this->pluginSettingsService->getSettings();

		$code = '<script type="text/javascript">
					var disqus_shortname = ' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue($shortName, TRUE) . ';
					var disqus_identifier = \'news_' . $newsItem->getUid() . '\';
					var disqus_url = ' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue($link, TRUE) . ';
					var disqus_title = ' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue($newsItem->getTitle(), TRUE) . ';
					var disqus_config = function () {
						this.language = ' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue($tsSettings['disqusLocale']) . ';
					};

					(function() {
						var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
						dsq.src = "http://" + disqus_shortname + ".disqus.com/embed.js";
						(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
					})();
				</script>';

		return $code;
	}
}
