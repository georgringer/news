<?php

declare(strict_types=1);

namespace GeorgRinger\News\Tests\Unit\Backend\FieldInformation;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Backend\FieldInformation\StaticText;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\BaseTestCase;

class StaticTextTest extends BaseTestCase
{
    use ProphecyTrait;

    /**
     * @var StaticText
     */
    protected $subject;

    /**
     * @var NodeFactory|ObjectProphecy
     */
    protected $nodeFactoryProphecy;

    /**
     * @var LanguageService|ObjectProphecy
     */
    protected $languageServiceProphecy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->languageServiceProphecy = $this->prophesize(LanguageService::class);
        $this->languageServiceProphecy
            ->sL('label1')
            ->willReturn('label bold');
        $this->languageServiceProphecy
            ->sL('label2')
            ->willReturn('label italic');
        $this->languageServiceProphecy
            ->sL('label3')
            ->willReturn('label both');
        $this->languageServiceProphecy
            ->sL('label4')
            ->willReturn('label unformatted');

        $GLOBALS['LANG'] = $this->languageServiceProphecy->reveal();

        $this->nodeFactoryProphecy = $this->prophesize(NodeFactory::class);
    }

    protected function tearDown(): void
    {
        unset(
            $this->subject,
            $this->nodeFactoryProphecy
        );

        parent::tearDown();
    }

    /**
     * @test
     */
    public function renderWillNotAddAnyLabels(): void
    {
        $this->subject = new StaticText(
            $this->nodeFactoryProphecy->reveal(),
            []
        );

        $nodeConfiguration = $this->subject->render();

        $this->assertArrayHasKey(
            'requireJsModules',
            $nodeConfiguration
        );

        $this->assertEquals(
            ['TYPO3/CMS/News/TagSuggestWizard'],
            $nodeConfiguration['requireJsModules']
        );
        $this->assertArrayHasKey(
            'html',
            $nodeConfiguration
        );

        $this->assertEquals(
            '<div class="form-control-wrap news-taggable"></div>',
            $nodeConfiguration['html']
        );
    }

    /**
     * @test
     */
    public function renderWillAddVariousFormattedLabels(): void
    {
        $this->subject = new StaticText(
            $this->nodeFactoryProphecy->reveal(),
            [
                'renderData' => [
                    'fieldInformationOptions' => [
                        'labels' => [
                            0 => [
                                'label' => 'label1',
                                'bold' => '1'
                            ],
                            1 => [
                                'label' => 'label2',
                                'italic' => 'something'
                            ],
                            2 => [
                                'label' => 'label3',
                                'italic' => 30,
                                'bold' => 'hello',
                            ],
                            3 => [
                                'label' => 'label4',
                            ],
                        ]
                    ]
                ]
            ]
        );

        $nodeConfiguration = $this->subject->render();

        $this->assertArrayHasKey(
            'html',
            $nodeConfiguration
        );

        $this->assertStringContainsString(
            '<strong>label bold</strong>',
            $nodeConfiguration['html']
        );
        $this->assertStringContainsString(
            '<em>label italic</em>',
            $nodeConfiguration['html']
        );
        $this->assertStringContainsString(
            '<strong><em>label both</em></strong>',
            $nodeConfiguration['html']
        );
        $this->assertStringContainsString(
            'label unformatted',
            $nodeConfiguration['html']
        );
    }

    /**
     * @test
     */
    public function renderWillDivideLabelsWithBreak(): void
    {
        $this->subject = new StaticText(
            $this->nodeFactoryProphecy->reveal(),
            [
                'renderData' => [
                    'fieldInformationOptions' => [
                        'labels' => [
                            0 => [
                                'label' => 'label1',
                            ],
                            1 => [
                                'label' => 'label2',
                            ],
                        ]
                    ]
                ]
            ]
        );

        $nodeConfiguration = $this->subject->render();

        $this->assertArrayHasKey(
            'html',
            $nodeConfiguration
        );

        $this->assertStringContainsString(
            'label bold<br />label italic',
            $nodeConfiguration['html']
        );
    }
}
