<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

use GeorgRinger\News\ViewHelpers\TargetLinkViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Test for TargetLinkViewHelper
 */
class TargetLinkViewHelperTest extends BaseTestCase
{
    /**
     * @return \GeorgRinger\News\ViewHelpers\TargetLinkViewHelper
     * @support
     */
    protected function getPreparedInstance()
    {
        $instance = $this->getMockBuilder(TargetLinkViewHelper::class)->setMethods(null)->getMock();
        return $instance;
    }

    /**
     * @test
     */
    public function canCreateViewHelperClassInstance(): void
    {
        $instance = $this->getPreparedInstance();
        self::assertInstanceOf(TargetLinkViewHelper::class, $instance);
    }

    /**
     * Test if correct target is returned
     *
     * @test
     *
     * @dataProvider correctTargetIsReturnedDataProvider
     */
    public function correctTargetIsReturned($link, $expectedResult): void
    {
        $viewHelper = $this->getMockBuilder(TargetLinkViewHelper::class)->setMethods(null)->getMock();
        $viewHelper->setRenderingContext($this->getMockBuilder(RenderingContextInterface::class)->getMock());
        $viewHelper->setArguments([
            'link' => $link,
        ]);

        self::assertEquals($viewHelper->render(), $expectedResult);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function correctTargetIsReturnedDataProvider()
    {
        return [
            'noTargetSetAndUrlDefined' => [
                'www.typo3.org', '',
            ],
            'noTargetSetAndIdDefined' => [
                '123', '',
            ],
            'IdAndTargetDefined' => [
                '123 _blank', '_blank',
            ],
            'UrlAndPopupDefined' => [
                'www.typo3.org 300x400', '',
            ],
            'ComplexExample' => [
                'www.typo3.org _fo my-class', '_fo',
            ],

        ];
    }
}
