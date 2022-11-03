<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\ViewHelpers\Iterator;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test for ChunkViewHelper
 */
class ChunkViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/news'];
    protected array $coreExtensionsToLoad = ['extbase', 'fluid'];

    /**
     * @test
     */
    public function chunkIsProperlyCreated(): void
    {
        $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $standaloneView->setTemplateSource(
            '{namespace n=GeorgRinger\News\ViewHelpers}' . LF .
            '<f:for each="{news -> n:iterator.chunk(count: 3)}" as="col" iteration="cycle">' .
            '<div class="row"><f:for each="{col}" as="item"><div class="col">{item.title}</div></f:for></div>' .
            '</f:for>'
        );
        $standaloneView->assign('news', [
            ['title' => 'el1'],
            ['title' => 'el2'],
            ['title' => 'el3'],
            ['title' => 'el4'],
        ]);

        $expected = '<div class="row">' .
            '<div class="col">el1</div>' .
            '<div class="col">el2</div>' .
            '<div class="col">el3</div>' .
            '</div>' .
            '<div class="row">' .
            '<div class="col">el4</div>' .
            '</div>';
        self::assertEquals(trim($expected), trim($standaloneView->render()));
    }
}
