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
 * Update class for extmgr.
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class ext_update {
	const STATUS_WARNING=-1;
	const STATUS_ERROR=0;
	const STATUS_OK=1;

	protected $messageArray = array();

	/**
	 * Renames a tabled field and does some plausibility checks.
	 *
	 * @param  string $table
	 * @param  string $oldFieldName
	 * @param  string $newFieldName
	 * @return int
	 */
	protected function renameTableField($table, $oldFieldName, $newFieldName) {
		$title = 'Renaming "' . $table . ':' . $oldFieldName . '" to "' . $table . ':' . $newFieldName . '": ';

		$currentTableFields = $GLOBALS['TYPO3_DB']->admin_get_fields($table);

		if ($currentTableFields[$newFieldName]) {
			$message = 'Field ' . $table . ':' . $newFieldName . ' already existing.';
			$status = self::STATUS_WARNING;
		} else {
			if (!$currentTableFields[$oldFieldName]) {
				$message = 'Field ' . $table . ':' . $oldFieldName . ' not existing';
				$status = self::STATUS_ERROR;
			} else {
				$sql = 'ALTER TABLE ' . $table . ' CHANGE COLUMN ' . $oldFieldName . ' ' . $newFieldName . ' ' .
					$currentTableFields[$oldFieldName]['Type'];

				if ($GLOBALS['TYPO3_DB']->admin_query($sql) === FALSE) {
					$message = ' SQL ERROR: ' .  $GLOBALS['TYPO3_DB']->sql_error();
					$status = self::STATUS_ERROR;
				} else {
					$message = 'OK!';
					$status = self::STATUS_OK;
				}

			}
		}

		$this->messageArray[] = array(status, $title, $message);
		return $status;
	}

	/**
	 * Main update function called by the extension manager.
	 *
	 * @return string
	 */
	public function main() {
		$this->processUpdates();

		return $this->generateOutput();
	}

	/**
	 * The actual update function. Add your update task in here.
	 *
	 * @return void
	 */
	protected function processUpdates() {
		$this->renameTableField('tx_news2_domain_model_news', 'cateories', 'categories');
	}

	/**
	 * Generates more or less readable output.
	 *
	 * @todo: beautify output :)
	 * @return string
	 */
	protected function generateOutput() {
		$output = '';
		foreach($this->messageArray as $messageItem) {
			$output .= '<strong>' . $messageItem[1] . '</strong><br />' .
				'&nbsp;&nbsp;&nbsp;->' . $messageItem[2] . '<br /><br />';
		}

		return $output;
	}

	/**
	 * Called by the extension manager to determine if the update menu entry should by showed.
	 *
	 * @return bool
	 * @todo find a better way to determine if update is needed or not.
	 */
	public function access() {
		return true;
	}
}
?>