<?php

namespace GeorgRinger\News\Tests\Unit\Backend\FormDataProvider;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Backend\FormDataProvider\NewsRowInitializeNew;
use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\TestingFramework\Core\BaseTestCase;

class NewsRowInitializeNewTest extends BaseTestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function dateTimeIsFilled(): void
    {
        $provider = $this->getAccessibleMock(NewsRowInitializeNew::class, ['dummy'], [], '', false);
        $mockedEmConfiguration = $this->getAccessibleMock(EmConfiguration::class, ['getDateTimeRequired'], [], '', false);
        $mockedEmConfiguration->expects($this->once())->method('getDateTimeRequired')->will($this->returnValue(true));

        $provider->_set('emConfiguration', $mockedEmConfiguration);

        $GLOBALS['EXEC_TIME'] = time();

        $result = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news'
        ];

        $expected = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news',
            'databaseRow' => [
                'datetime' => $GLOBALS['EXEC_TIME']
            ]
        ];

        $this->assertEquals($expected, $provider->addData($result));
    }

    /**
     * @test
     *
     * @return void
     */
    public function dateTimeIsNotFilledIfSetInExtensionManagerConfiguration(): void
    {
        $mockedProvider = $this->getAccessibleMock(NewsRowInitializeNew::class, ['dummy'], [], '', false);
        $configuration = ['dateTimeNotRequired' => true];
        $settings = new EmConfiguration($configuration);
        $mockedProvider->_set('emConfiguration', $settings);

        $result = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news'
        ];
        $expected = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news',
        ];

        $this->assertEquals($expected, $mockedProvider->addData($result));
    }

    /**
     * @test
     *
     * @return void
     */
    public function archiveTimeIsFilled(): void
    {
        $provider = $this->getAccessibleMock(NewsRowInitializeNew::class, ['dummy'], [], '', false);
        $mockedEmConfiguration = $this->getAccessibleMock(EmConfiguration::class, ['getDateTimeRequired'], [], '', false);
        $mockedEmConfiguration->expects($this->once())->method('getDateTimeRequired')->will($this->returnValue(true));

        $provider->_set('emConfiguration', $mockedEmConfiguration);

        $GLOBALS['EXEC_TIME'] = time();

        $result = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news',
            'pageTsConfig' => [
                'tx_news.' => [
                    'predefine.' => [
                        'archive' => '+10 days'
                    ]
                ]
            ]
        ];

        $expected = $result;
        $expected['databaseRow']['datetime'] = $GLOBALS['EXEC_TIME'];
        $expected['databaseRow']['archive'] = strtotime('+10 days');

        $this->assertEquals($expected, $provider->addData($result));
    }
}
