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
 */
class TypoScriptTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @test
     * @dataProvider overrideWorksDataProvider
     */
    public function overrideWorks($base, $overload, $expected)
    {
        $utility = new TypoScript();

        $result = $utility->override($base, $overload);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function overrideWorksDataProvider()
    {
        return [
            'basic' => [
                [
                    'value_1' => 'fo',
                    'value_2' => ''
                ],
                [],
                [
                    'value_1' => 'fo',
                    'value_2' => ''
                ]
            ],
            'simple' => [
                [
                    'value_1' => 'fo',
                    'value_2' => ''
                ],
                [
                    'settings' => [
                        'value_2' => 'bar',
                        'overrideFlexformSettingsIfEmpty' => 'value_2'
                    ]
                ],
                [
                    'value_1' => 'fo',
                    'value_2' => 'bar'
                ]
            ],
            'simple2' => [
                [
                    'value_1' => 'fo',
                    'sub' => [
                        'sub_1' => 'xy'
                    ]
                ],
                [
                    'settings' => [
                        'value_2' => 'bar',
                        'overrideFlexformSettingsIfEmpty' => 'value_2'
                    ]
                ],
                [
                    'value_1' => 'fo',
                    'value_2' => 'bar',
                    'sub' => [
                        'sub_1' => 'xy'
                    ]
                ]
            ],
            'deep' => [
                [
                    'value_1' => 'fo',
                    'sub_1' => [
                        'sub_1a' => ''
                    ],
                    'sub_2' => [
                        'sub_2a' => 'xy',
                    ],
                ],
                [
                    'settings' => [
                        'value_2' => 'bar',
                        'sub_1' => [
                            'sub_1a' => 'lorem'
                        ],
                        'sub_2' => [
                            'sub_2a' => 'xy',
                            'sub_2b' => 'abc'
                        ],
                        'overrideFlexformSettingsIfEmpty' => 'value_2, sub_1.sub_1a,sub_2.sub_2b'
                    ]
                ],
                [
                    'value_1' => 'fo',
                    'value_2' => 'bar',
                    'sub_1' => [
                        'sub_1a' => 'lorem'
                    ],
                    'sub_2' => [
                        'sub_2a' => 'xy',
                    ],
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider correctValueIsReturnedDataProvider
     */
    public function correctValueIsReturned($path, $expected)
    {
        $mockedUtility = $this->getAccessibleMock('GeorgRinger\\News\\Utility\\TypoScript', ['dummy']);

        $in = [
            'level_1' => [
                'in_1' => 'value in 1',
                'level_2' => [
                    'level_3' => [
                        'in_3' => 'value in 3'
                    ]
                ]
            ]
        ];

        $path = explode('.', $path);
        $result = $mockedUtility->_call('getValue', $in, $path);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function correctValueIsReturnedDataProvider()
    {
        return [
            'valueFoundInDeepLevel' => [
                'level_1.level_2.level_3.in_3', 'value in 3'
            ],
            'valueFoundInFirstLevel' => [
                'level_1.in_1', 'value in 1'
            ],
            'firstLevelNotFound' => [
                'wrong.wronger.stillWrong', null
            ],
            'lastLevelNotFound' => [
                'level_1.level_2.level_3.in_Nothing', null
            ],
        ];
    }

    /**
     * @test
     * @dataProvider correctValueIsSetDataProvider
     */
    public function correctValueIsSet($path, $newValue, $expected)
    {
        $mockedUtility = $this->getAccessibleMock('GeorgRinger\\News\\Utility\\TypoScript', ['dummy'], [], '', true, false);

        $in = [
            'level_1' => [
                'in_1' => 'value in 1',
                'level_2' => [
                    'level_3' => [
                        'in_3' => 'value in 3'
                    ]
                ]
            ]
        ];

        $path = explode('.', $path);
        $result = $mockedUtility->_call('setValue', $in, $path, $newValue);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function correctValueIsSetDataProvider()
    {
        return [
            'overrideValueLow' => [
                'level_1.in_1',
                'new value in 1',
                [
                    'level_1' => [
                        'in_1' => 'new value in 1',
                        'level_2' => [
                            'level_3' => [
                                'in_3' => 'value in 3'
                            ]
                        ]
                    ]
                ]
            ],
            'overrideValueDeep' => [
                'level_1.level_2.level_3.in_3',
                'new value in 3',
                [
                    'level_1' => [
                        'in_1' => 'value in 1',
                        'level_2' => [
                            'level_3' => [
                                'in_3' => 'new value in 3'
                            ]
                        ]
                    ]
                ]
            ],
            'newValueDeep' => [
                'level_1.level_2.level_3.level_4.level_5.in_5',
                'new value in 5',
                [
                    'level_1' => [
                        'in_1' => 'value in 1',
                        'level_2' => [
                            'level_3' => [
                                'in_3' => 'value in 3',
                                'level_4' => [
                                    'level_5' => [
                                        'in_5' => 'new value in 5'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'overrideArrayWithValue' => [
                'level_1.level_2',
                'new value as 2',
                [
                    'level_1' => [
                        'in_1' => 'value in 1',
                        'level_2' => 'new value as 2'
                    ]
                ]
            ],
        ];
    }
}
