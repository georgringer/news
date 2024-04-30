<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Backend\FormDataProvider;

use GeorgRinger\News\Backend\FormDataProvider\NewsRowInitializeNew;
use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class NewsRowInitializeNewTest extends BaseTestCase
{
    /**
     * @test
     */
    public function dateTimeIsFilled(): void
    {
        $provider = $this->getAccessibleMock(NewsRowInitializeNew::class, null, [], '', false);
        $mockedEmConfiguration = $this->getAccessibleMock(EmConfiguration::class, ['getDateTimeRequired'], [], '', false);
        $mockedEmConfiguration->expects(self::once())->method('getDateTimeRequired')->willReturn(true);

        $provider->_set('emConfiguration', $mockedEmConfiguration);

        $GLOBALS['EXEC_TIME'] = time();

        $result = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news',
        ];

        $expected = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news',
            'databaseRow' => [
                'datetime' => GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp'),
            ],
        ];

        self::assertEquals($expected, $provider->addData($result));
    }

    /**
     * @test
     */
    public function dateTimeIsNotFilledIfSetInExtensionManagerConfiguration(): void
    {
        $mockedProvider = $this->getAccessibleMock(NewsRowInitializeNew::class, null, [], '', false);
        $configuration = ['dateTimeNotRequired' => true];
        $settings = new EmConfiguration($configuration);
        $mockedProvider->_set('emConfiguration', $settings);

        $result = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news',
        ];

        self::assertSame($result, $mockedProvider->addData($result));
    }

    /**
     * @test
     */
    public function archiveTimeIsFilled(): void
    {
        $provider = $this->getAccessibleMock(NewsRowInitializeNew::class, null, [], '', false);
        $mockedEmConfiguration = $this->getAccessibleMock(EmConfiguration::class, ['getDateTimeRequired'], [], '', false);
        $mockedEmConfiguration->expects(self::once())->method('getDateTimeRequired')->willReturn(true);

        $provider->_set('emConfiguration', $mockedEmConfiguration);

        $GLOBALS['EXEC_TIME'] = time();

        $result = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news',
            'pageTsConfig' => [
                'tx_news.' => [
                    'predefine.' => [
                        'archive' => '+10 days',
                    ],
                ],
            ],
        ];

        self::assertSame(strtotime('+10 days'), $provider->addData($result)['databaseRow']['archive']);
    }
}
