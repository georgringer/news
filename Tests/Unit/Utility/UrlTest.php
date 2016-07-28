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
use GeorgRinger\News\Utility\Url;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test class for Url
 *
 */
class UrlTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
