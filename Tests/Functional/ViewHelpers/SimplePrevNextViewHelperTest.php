<?php

namespace GeorgRinger\News\Tests\Unit\Functional\ViewHelpers;

/*
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
use GeorgRinger\News\Domain\Model\News;

/**
 * Class SimplePrevNextViewHelperTest.
 */
class SimplePrevNextViewHelperTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface|\GeorgRinger\News\ViewHelpers\SimplePrevNextViewHelper */
    protected $mockedViewHelper;

    /** @var \GeorgRinger\News\Domain\Model\News */
    protected $news;

    protected $testExtensionsToLoad = ['typo3conf/ext/news'];
    protected $coreExtensionsToLoad = ['extbase', 'fluid'];

    public function setUp()
    {
        parent::setUp();
        $this->mockedViewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\SimplePrevNextViewHelper', ['dummy'], [], '', true, true, false);

        $this->news = new News();
        $this->news->setPid(9);

        $this->importDataSet(__DIR__.'/../Fixtures/tx_news_domain_model_news.xml');
    }

    /**
     * @test
     *
     * @return void
     */
    public function allNeighboursCanBeFound()
    {
        $this->news->_setProperty('uid', 103);

        $fo = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');

        $exp = [
            0 => [
                'uid'   => 102,
                'title' => null,
            ],
            1 => [
                'uid'   => 103,
                'title' => null,
            ],
            2 => [
                'uid'   => 104,
                'title' => null,
            ],
        ];
        $this->assertEquals($exp, $fo);
    }

    /**
     * @test
     *
     * @return void
     */
    public function nextNeighbourCanBeFound()
    {
        $this->news->_setProperty('uid', 101);

        $fo = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');

        $exp = [
            0 => [
                'uid'   => 102,
                'title' => null,
            ],
        ];
        $this->assertEquals($exp, $fo);
    }

    /**
     * @test
     *
     * @return void
     */
    public function previousNeighbourCanBeFound()
    {
        $this->news->_setProperty('uid', 106);

        $fo = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');

        $exp = [
            0 => [
                'uid'   => 105,
                'title' => null,
            ],
            1 => [
                'uid'   => 106,
                'title' => null,
            ],
        ];
        $this->assertEquals($exp, $fo);
    }
}
