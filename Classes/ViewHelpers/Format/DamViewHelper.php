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
 * ViewHelper to get full dam record
 * Example
 * <n:format.dam as="dam" uid="123">
 *    <f:image src="{dam.file_path}{dam.file_name}"
 * 		title="{dam.title}"
 * 		alt="{dam.alt_text}"
 * 		maxWidth="200" />
 * </n:format.dam>
 * Will output the dam record with uid 123 by using the image ViewHelper
 * Be aware that the file could be anything, e.g. a doc file or video,
 * so also check {dam.file_mime_type}
 * Example II
 * <f:debug>{dam}</f:debug>
 * Will output the whole record
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Format_DamViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Return dam record
	 *
	 * @param integer $uid uid of media element.
	 * @param string $as name of element which is used for the dam record
	 * @return string
	 */
	public function render($uid, $as) {
		if (!t3lib_extMgm::isLoaded('dam')) {
			throw new Tx_Fluid_Core_ViewHelper_Exception('DamViewHelper needs a loaded DAM extension', 1318786684);
		}

		if ($GLOBALS['TSFE']->sys_language_content > 0) {
			$media = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tx_news_domain_model_media', 'deleted=0 AND uid =' . $uid);
			$media = $GLOBALS['TSFE']->sys_page->getRecordOverlay('tx_news_domain_model_media', $media, $GLOBALS['TSFE']->sys_language_content);
			if ($media['_LOCALIZED_UID'] > 0) {
				// Does this localized media has dam record?
				$where = 'uid_foreign =' . (int)$media['_LOCALIZED_UID'] . ' AND tablenames =\'tx_news_domain_model_media\' AND ident = \'tx_news_media\'';
				$damRec = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('uid_local', 'tx_dam_mm_ref', $where);
				if (is_array($damRec) && $damRec['uid_local']) {
					$uid = $media['_LOCALIZED_UID'];
				}
			}
		}

		$res = tx_dam_db::referencesQuery(
			'tx_dam',
			'',
			'tx_news_domain_model_media',
			(int)$uid,
			$mmIdent = '',
			$mmTable = 'tx_dam_mm_ref',
			$fields = 'tx_dam.*');

		$record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		$this->templateVariableContainer->add($as, $record);
		$output = $this->renderChildren();
		$this->templateVariableContainer->remove($as);

		return $output;
	}
}

?>