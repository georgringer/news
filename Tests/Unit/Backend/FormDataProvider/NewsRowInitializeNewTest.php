<?php

namespace GeorgRinger\News\Unit\Backend\FormDataProvider;

/*
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

use GeorgRinger\News\Backend\FormDataProvider\NewsRowInitializeNew;

class NewsRowInitializeNewTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function dateTimeIsFilled() {
		$provider = new NewsRowInitializeNew();

		$GLOBALS['EXEC_TIME'] = time();

		$result = array(
			'command' => 'new',
			'tableName' => 'tx_news_domain_model_news'
		);

		$expected = array(
			'command' => 'new',
			'tableName' => 'tx_news_domain_model_news',
			'databaseRow' => array(
				'datetime' => $GLOBALS['EXEC_TIME']
			)
		);

		$this->assertEquals($expected, $provider->addData($result));
	}

		/**
	 * @test
	 */
	public function archiveTimeIsFilled() {
		$provider = new NewsRowInitializeNew();

		$result = array(
			'command' => 'new',
			'tableName' => 'tx_news_domain_model_news',
			'pageTsConfig' => array(
				'tx_news.' => array(
					'predefine.' => array(
						'archive' => '+10 days'
					)
				)
			)
		);

		$expected = $result;
		$expected['databaseRow']['datetime'] = time();
		$expected['databaseRow']['archive'] = strtotime('+10 days');

		$this->assertEquals($expected, $provider->addData($result));
	}

}