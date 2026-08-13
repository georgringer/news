<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Frontend;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;

class CategoryListTest extends AbstractFrontendTestCase
{
    #[IgnoreDeprecations]
    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function listActionRendersCategoryTitle(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $html = $this->assertRendersWithoutError($this->renderPage(4));

        self::assertStringContainsString('Rendering Test Category', $html);
    }

    /**
     * The templates render different markup for a selected category than for
     * an unselected one, so both branches need coverage.
     */
    #[IgnoreDeprecations]
    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function listActionRendersActiveCategory(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $response = $this->renderPage(4, [
            'tx_news_pi1[overwriteDemand][categories]' => 1,
        ]);
        $html = $this->assertRendersWithoutError($response);

        self::assertStringContainsString('Rendering Test Category', $html);
    }
}
