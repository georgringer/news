<?php

namespace GeorgRinger\News\Tests\Unit\Utility;

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
use GeorgRinger\News\Utility\TypoScript;

/**
 * Test class for TypoScript
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class TypoScriptTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @dataProvider overrideWorksDataProvider
	 */
	public function overrideWorks($base, $overload, $expected) {
		$utility = new TypoScript();

		$result = $utility->override($base, $overload);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function overrideWorksDataProvider() {
		return array(
			'basic' => array(
				array(
					'value_1' => 'fo',
					'value_2' => ''
				),
				array(),
				array(
					'value_1' => 'fo',
					'value_2' => ''
				)
			),
			'simple' => array(
				array(
					'value_1' => 'fo',
					'value_2' => ''
				),
				array(
					'settings' => array(
						'value_2' => 'bar',
						'overrideFlexformSettingsIfEmpty' => 'value_2'
					)
				),
				array(
					'value_1' => 'fo',
					'value_2' => 'bar'
				)
			),
			'simple2' => array(
				array(
					'value_1' => 'fo',
					'sub' => array(
						'sub_1' => 'xy'
					)
				),
				array(
					'settings' => array(
						'value_2' => 'bar',
						'overrideFlexformSettingsIfEmpty' => 'value_2'
					)
				),
				array(
					'value_1' => 'fo',
					'value_2' => 'bar',
					'sub' => array(
						'sub_1' => 'xy'
					)
				)
			),
			'deep' => array(
				array(
					'value_1' => 'fo',
					'sub_1' => array(
						'sub_1a' => ''
					),
					'sub_2' => array(
						'sub_2a' => 'xy',
					),
				),
				array(
					'settings' => array(
						'value_2' => 'bar',
						'sub_1' => array(
							'sub_1a' => 'lorem'
						),
						'sub_2' => array(
							'sub_2a' => 'xy',
							'sub_2b' => 'abc'
						),
						'overrideFlexformSettingsIfEmpty' => 'value_2, sub_1.sub_1a,sub_2.sub_2b'
					)
				),
				array(
					'value_1' => 'fo',
					'value_2' => 'bar',
					'sub_1' => array(
						'sub_1a' => 'lorem'
					),
					'sub_2' => array(
						'sub_2a' => 'xy',
					),
				)
			),
		);
	}


	/**
	 * @test
	 * @dataProvider correctValueIsReturnedDataProvider
	 */
	public function correctValueIsReturned($path, $expected) {
		$mockedUtility = $this->getAccessibleMock('GeorgRinger\\News\\Utility\\TypoScript', array('dummy'));

		$in = array(
			'level_1' => array(
				'in_1' => 'value in 1',
				'level_2' => array(
					'level_3' => array(
						'in_3' => 'value in 3'
					)
				)
			)
		);

		$path = explode('.', $path);
		$result = $mockedUtility->_call('getValue', $in, $path);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function correctValueIsReturnedDataProvider() {
		return array(
			'valueFoundInDeepLevel' => array(
				'level_1.level_2.level_3.in_3', 'value in 3'
			),
			'valueFoundInFirstLevel' => array(
				'level_1.in_1', 'value in 1'
			),
			'firstLevelNotFound' => array(
				'wrong.wronger.stillWrong', NULL
			),
			'lastLevelNotFound' => array(
				'level_1.level_2.level_3.in_Nothing', NULL
			),
		);
	}

	/**
	 * @test
	 * @dataProvider correctValueIsSetDataProvider
	 */
	public function correctValueIsSet($path, $newValue, $expected) {
		$mockedUtility = $this->getAccessibleMock('GeorgRinger\\News\\Utility\\TypoScript', array('dummy'), array(), '', TRUE, FALSE);

		$in = array(
			'level_1' => array(
				'in_1' => 'value in 1',
				'level_2' => array(
					'level_3' => array(
						'in_3' => 'value in 3'
					)
				)
			)
		);

		$path = explode('.', $path);
		$result = $mockedUtility->_call('setValue', $in, $path, $newValue);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function correctValueIsSetDataProvider() {
		return array(
			'overrideValueLow' => array(
				'level_1.in_1',
				'new value in 1',
				array(
					'level_1' => array(
						'in_1' => 'new value in 1',
						'level_2' => array(
							'level_3' => array(
								'in_3' => 'value in 3'
							)
						)
					)
				)
			),
			'overrideValueDeep' => array(
				'level_1.level_2.level_3.in_3',
				'new value in 3',
				array(
					'level_1' => array(
						'in_1' => 'value in 1',
						'level_2' => array(
							'level_3' => array(
								'in_3' => 'new value in 3'
							)
						)
					)
				)
			),
			'newValueDeep' => array(
				'level_1.level_2.level_3.level_4.level_5.in_5',
				'new value in 5',
				array(
					'level_1' => array(
						'in_1' => 'value in 1',
						'level_2' => array(
							'level_3' => array(
								'in_3' => 'value in 3',
								'level_4' => array(
									'level_5' => array(
										'in_5' => 'new value in 5'
									)
								)
							)
						)
					)
				)
			),
			'overrideArrayWithValue' => array(
				'level_1.level_2',
				'new value as 2',
				array(
					'level_1' => array(
						'in_1' => 'value in 1',
						'level_2' => 'new value as 2'
					)
				)
			),
		);
	}

}
