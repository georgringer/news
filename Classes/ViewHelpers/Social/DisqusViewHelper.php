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
 * ViewHelper to add disqus thread
 * Details: http://www.disqus.com/
 *
 * Example
 * ==============
 * <div id="disqus_thread"></div>
 * <n:social.disqus newsItem="{newsItem}" shortName="derfilm" link="{n:link(newsItem:newsItem,settings:settings,linkOnly:1)}" />
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class Tx_News2_ViewHelpers_Social_DisqusViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	protected $escapingInterceptorEnabled = FALSE;

	/**
	 * Render disqus thread
	 *
	 * @param Tx_News2_Domain_Model_News $newsItem news item
	 * @param string $shortName shortname
	 * @param string $link link
	 * @return string
	 */
	public function render(Tx_News2_Domain_Model_News $newsItem, $shortName, $link) {
		$code = '<script type="text/javascript">
					var disqus_shortname = "' . htmlspecialchars($shortName) . '";
					 var disqus_identifier = "news_' . $newsItem->getUid() . '";
					 var disqus_url = "' . htmlspecialchars($link) . '";

					(function() {
						var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
						dsq.src = "http://" + disqus_shortname + ".disqus.com/embed.js";
						(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
					})();
				</script>';

		return $code;
	}
}

?>