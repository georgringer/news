<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2011 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * ViewHelper to render extended objects
 *
 * # Example: Basic example
 * <code>
 * <n:object newsItem="{newsItem}"
 * 		as="out"
 * 		className="Tx_Myext_Domain_Model_CustomModel" >
 * {out.fo}
 * </n:link>
 * </code>
 * <output>
 * Property "fo" from model Tx_Myext_Domain_Model_CustomModel
 * which extends the table tx_news_domain_model_news
 *
 * !!Be aware that this needs a mapping in TS!!
 *    config.tx_extbase.persistence.classes {
 *        Tx_Myext_Domain_Model_CustomModel {
 *             mapping {
 *                tableName = tx_news_domain_model_news
 *            }
 *        }
 *    }
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_ObjectViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Output different objects
	 *
	 * @param Tx_News_Domain_Model_News $newsItem current newsitem
	 * @param string $as output variable
	 * @param string $className custom class which handles the new objects
	 * @param string $extendedTable table which is extended
	 * @return string output
	 */
	public function render(Tx_News_Domain_Model_News $newsItem, $as, $className, $extendedTable = 'tx_news_domain_model_news') {
		$rawRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', $extendedTable, 'uid=' . (int)$newsItem->getUid());
		$rawRecord = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
			$extendedTable,
			$rawRecord,
			$GLOBALS['TSFE']->sys_language_content,
			$GLOBALS['TSFE']->sys_language_contentOL);

		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		/* @var $dataMapper \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper */
		$dataMapper = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Mapper\\DataMapper');

		$records = $dataMapper->map($className, array($rawRecord));
		$record = array_shift($records);

		$this->templateVariableContainer->add($as, $record);
		$output = $this->renderChildren();
		$this->templateVariableContainer->remove($as);
		return $output;
	}
}
