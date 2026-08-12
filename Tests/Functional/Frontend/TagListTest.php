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

class TagListTest extends AbstractFrontendTestCase
{
    #[IgnoreDeprecations]
    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function listActionRendersTagTitle(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $html = $this->assertRendersWithoutError($this->renderPage(5));

        self::assertStringContainsString('Rendering Test Tag', $html);
    }

    #[IgnoreDeprecations]
    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function listActionRendersActiveTag(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $response = $this->renderPage(5, [
            'tx_news_pi1[overwriteDemand][tags]' => 1,
        ]);
        $html = $this->assertRendersWithoutError($response);

        self::assertStringContainsString('Rendering Test Tag', $html);
    }
}
