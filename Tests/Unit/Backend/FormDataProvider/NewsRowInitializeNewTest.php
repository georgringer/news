<?php

namespace GeorgRinger\News\Unit\Backend\FormDataProvider;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Backend\FormDataProvider\NewsRowInitializeNew;
use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class NewsRowInitializeNewTest extends UnitTestCase
{

    /**
     * @test
     */
    public function dateTimeIsFilled()
    {
        $provider = new NewsRowInitializeNew();

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
     */
    public function dateTimeIsNotFilledIfSetInExtensionManagerConfiguration()
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
     */
    public function archiveTimeIsFilled()
    {
        $provider = new NewsRowInitializeNew();
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
