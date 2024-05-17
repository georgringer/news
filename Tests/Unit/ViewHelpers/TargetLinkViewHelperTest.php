<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use GeorgRinger\News\ViewHelpers\TargetLinkViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Test for TargetLinkViewHelper
 *
 * @todo Consider refactor this as functional test.
 */
class TargetLinkViewHelperTest extends BaseTestCase
{
    /**
     * @return TargetLinkViewHelper
     * @support
     */
    protected function getPreparedInstance()
    {
        return $this->getMockBuilder(TargetLinkViewHelper::class)->getMock();
    }

    #[Test]
    public function canCreateViewHelperClassInstance(): void
    {
        $instance = $this->getPreparedInstance();
        self::assertInstanceOf(TargetLinkViewHelper::class, $instance);
    }

    /**
     * Test if correct target is returned
     */
    #[DataProvider('correctTargetIsReturnedDataProvider')]
    #[Test]
    public function correctTargetIsReturned($link, $expectedResult): void
    {
        $viewHelper = $this->getMockBuilder(TargetLinkViewHelper::class)->getMock();
        $viewHelper->setRenderingContext($this->getMockBuilder(RenderingContextInterface::class)->getMock());
        $viewHelper->setArguments([
            'link' => $link,
        ]);

        self::assertEquals($expectedResult, $viewHelper->render());
    }

    /**
     * Data provider
     *
     * @return array
     */
    public static function correctTargetIsReturnedDataProvider()
    {
        return [
            'noTargetSetAndUrlDefined' => [
                'link' => 'www.typo3.org',
                'expectedResult' => '',
            ],
            'noTargetSetAndIdDefined' => [
                'link' => '123',
                'expectedResult' => '',
            ],
            'IdAndTargetDefined' => [
                'link' => '123 _blank',
                'expectedResult' => '_blank',
            ],
            'UrlAndPopupDefined' => [
                'link' => 'www.typo3.org 300x400',
                'expectedResult' => '',
            ],
            'ComplexExample' => [
                'link' => 'www.typo3.org _fo my-class',
                'expectedResult' => '_fo',
            ],

        ];
    }
}
