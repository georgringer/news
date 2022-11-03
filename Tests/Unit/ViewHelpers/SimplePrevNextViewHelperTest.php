<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

use GeorgRinger\News\ViewHelpers\SimplePrevNextViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Test for SimplePrevNextViewHelper
 */
class SimplePrevNextViewHelperTest extends BaseTestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface */
    protected $viewHelper;

    public function setup(): void
    {
        $this->viewHelper = $this->getAccessibleMock(SimplePrevNextViewHelper::class, ['getRawRecord']);
    }

    /**
     * @test
     */
    public function wrongIdWillReturnNullForObject(): void
    {
        $this->viewHelper->expects(self::any())->method('getRawRecord')->withAnyParameters()->willReturn(null);

        $out = $this->viewHelper->_call('getObject', 0);
        self::assertEquals($out, null);
    }

    /**
     * @test
     */
    public function queryResultWillReturnCorrectOutputForAllLinks(): void
    {
        $viewHelper = $this->getAccessibleMock(SimplePrevNextViewHelper::class, ['getObject']);

        $in = [
            'prev' => ['uid' => 123],
            'next' => ['uid' => 789],
        ];
        $exp = ['prev' => 123, 'next' => 789];
        $viewHelper->expects(self::exactly(2))->method('getObject')->willReturnOnConsecutiveCalls(123, 789);
        $out = $viewHelper->_call('mapResultToObjects', $in);
        self::assertEquals($out, $exp);
    }

    /**
     * @test
     */
    public function queryResultWillReturnCorrectOutputFor2Links(): void
    {
        $viewHelper = $this->getAccessibleMock(SimplePrevNextViewHelper::class, ['getObject']);

        $in = [
            'prev' => ['uid' => 147],
        ];
        $exp = ['prev' => 147];
        $viewHelper->expects(self::exactly(1))->method('getObject')->willReturnOnConsecutiveCalls(147);
        $out = $viewHelper->_call('mapResultToObjects', $in);
        self::assertEquals($out, $exp);
    }

    /**
     * @test
     */
    public function queryResultWillReturnCorrectOutputFor1Link(): void
    {
        $viewHelper = $this->getAccessibleMock(SimplePrevNextViewHelper::class, ['getObject']);

        $in = [
            'next' => ['uid' => 369],
        ];
        $exp = ['next' => 369];
        $viewHelper->expects(self::exactly(1))->method('getObject')->willReturnOnConsecutiveCalls(369);
        $out = $viewHelper->_call('mapResultToObjects', $in);
        self::assertEquals($out, $exp);
    }
}
