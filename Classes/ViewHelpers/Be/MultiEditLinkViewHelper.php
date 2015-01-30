<?php

namespace GeorgRinger\News\ViewHelpers\Be;

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
 * ViewHelper to create javascript to edit fields of multiple records
 *
 * # Example: Basic example
 * <code>
 * <n:be.buttons.icon uri="#" onclick="{n:be.multiEditLink(items:news,columns:'title')}" icon="actions-document-open" />
 * </code>
 * <output>
 * Onclick event which can be used to create a link to edit all title fields of given news records
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class MultiEditLinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render the onclick JavaScript for editing given fields of given news records
	 *
	 * @param object $items news items
	 * @param string $columns column names
	 * @return string
	 */
	public function render($items, $columns) {
		$idList = array();
		foreach ($items as $item) {
			$idList[] = $item->getUid();
		}

		$content = 'window.location.href=\'alt_doc.php?returnUrl=\'+T3_THIS_LOCATION+\'&edit[tx_news_domain_model_news][' .
			implode(',', $idList) .
			']=edit&columnsOnly=' . $columns . '&disHelp=1\';return false;';
		return $content;
	}
}
