<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\ViewHelpers\Iterator;

use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test for ChunkViewHelper
 */
class ChunkViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/news'];
    protected array $coreExtensionsToLoad = ['extbase', 'fluid'];

    #[Test]
    #[IgnoreDeprecations]
    public function chunkIsProperlyCreated(): void
    {
        $viewFactoryData = new ViewFactoryData(
            templatePathAndFilename: 'EXT:news/Tests/Functional/Fixtures/ChunkViewHelperFixture.html'
        );
        $standaloneView = GeneralUtility::makeInstance(ViewFactoryInterface::class)->create($viewFactoryData);
        $standaloneView->assign('news', [
            ['title' => 'el1'],
            ['title' => 'el2'],
            ['title' => 'el3'],
            ['title' => 'el4'],
        ]);

        $expected = '<div class="row"><div class="col">el1</div><div class="col">el2</div><div class="col">el3</div>'
            . '</div>'
            . '<div class="row">'
            . '<div class="col">el4</div>'
            . '</div>';
        self::assertEquals(trim($expected), trim($standaloneView->render()));
    }
}
