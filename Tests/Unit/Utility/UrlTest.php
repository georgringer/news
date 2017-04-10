<?php

namespace GeorgRinger\News\Tests\Unit\Utility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Utility\Url;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test class for Url
 *
 */
class UrlTest extends UnitTestCase
{

    /**
     * @test
     * @dataProvider correctUrlIsDeliveredDataProvider
     */
    public function correctUrlIsDelivered($actual, $expected)
    {
        $this->assertEquals($expected, Url::prependDomain($actual));
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function correctUrlIsDeliveredDataProvider()
    {
        $currentDomain = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
        return [
            'absoluteUrlIsUsed' => [
                $currentDomain . 'index.php?id=123', $currentDomain . 'index.php?id=123'
            ],
            'relativeUrlIsUsed' => [
                'index.php?id=123', $currentDomain . 'index.php?id=123'
            ],
            'domainOnlyIsGiven' => [
                $currentDomain, $currentDomain
            ],
        ];
    }
}
