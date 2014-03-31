<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
class Tx_News_ViewHelpers_Be_MultiEditLinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

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
