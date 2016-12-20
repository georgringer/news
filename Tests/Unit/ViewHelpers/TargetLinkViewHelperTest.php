<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

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
use GeorgRinger\News\ViewHelpers\TargetLinkViewHelper;

/**
 * Test for TargetLinkViewHelper
 */
class TargetLinkViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @return \GeorgRinger\News\ViewHelpers\TargetLinkViewHelper
     * @support
     */
    protected function getPreparedInstance()
    {
        $instance = new TargetLinkViewHelper();
        return $instance;
    }

    /**
     * @test
     */
    public function canCreateViewHelperClassInstance()
    {
        $instance = $this->getPreparedInstance();
        $this->assertInstanceOf(TargetLinkViewHelper::class, $instance);
    }

    /**
     * Test if correct target is returned
     *
     * @test
     * @dataProvider correctTargetIsReturnedDataProvider
     * @return void
     */
    public function correctTargetIsReturned($link, $expectedResult)
    {
        $viewHelper = new TargetLinkViewHelper();
        $viewHelper->setArguments([
            'link' => $link,
        ]);

        $this->assertEquals($viewHelper->render(), $expectedResult);
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
                'www.typo3.org', ''
            ],
            'noTargetSetAndIdDefined' => [
                '123', ''
            ],
            'IdAndTargetDefined' => [
                '123 _blank', '_blank'
            ],
            'UrlAndPopupDefined' => [
                'www.typo3.org 300x400', ''
            ],
            'ComplexExample' => [
                'www.typo3.org _fo my-class', '_fo'
            ],

        ];
    }
}
