<?php

namespace GeorgRinger\News\Updates;

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

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
class TtContentRelation extends AbstractUpdate {

	const MM_TABLE = 'tx_news_domain_model_news_ttcontent_mm';

	protected $title = 'EXT:news Migrate from tt_content mm relation';

	/**
	 * Checks whether updates are required.
	 *
	 * @param string &$description The description for the update
	 * @return bool Whether an update is required (TRUE) or not (FALSE)
	 */
	public function checkForUpdate(&$description) {
		$status = FALSE;

		$databaseTables = $this->getDatabaseConnection()->admin_get_tables();
		if (!isset($databaseTables[self::MM_TABLE])) {
			$description = sprintf('The database table "%s" does not exist, nothing to do!', self::MM_TABLE);
		} else {
			$countRows = $this->getDatabaseConnection()->exec_SELECTcountRows('*', self::MM_TABLE, '1=1');
			if ($countRows === 0) {
				$description = sprintf('The database table "%s" is empty, nothing to do', self::MM_TABLE);
			} else {
				$description = sprintf('The database table "%s" contains <strong>%s</strong> entries which will be migrated!', self::MM_TABLE, $countRows);

				$firstContentElementRow = $this->getDatabaseConnection()->exec_SELECTgetSingleRow('*', 'tt_content', '1=1');
				if (!isset($firstContentElementRow['tx_news_related_news'])) {
					$status = FALSE;
					$description .= '<br><strong>WARNING:</strong> The database table "tt_content" does not contain the column <i>tx_news_related_news</i> which is needed!';
					$description .= '<br>Use the Database Compare to add this field!';
				} else {
					$status = TRUE;
				}

			}
		}

		return $status;
	}

	/**
	 * Performs the accordant updates.
	 *
	 * @param array &$dbQueries Queries done in this update
	 * @param mixed &$customMessages Custom messages
	 * @return bool Whether everything went smoothly or not
	 */
	public function performUpdate(array &$dbQueries, &$customMessages) {
		$rows = $this->getDatabaseConnection()->exec_SELECTgetRows('*', self::MM_TABLE, '1=1');
		foreach ($rows as $row) {
			$update = array(
				'tx_news_related_news' => $row['uid_local'],
				'sorting' => $row['sorting']
			);
			$this->getDatabaseConnection()->exec_UPDATEquery(
				'tt_content',
				'uid=' . $row['uid_foreign'],
				$update
			);
		}

		return TRUE;
	}


	/**
	 * @return DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}
}