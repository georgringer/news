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

class NewsDateMenuTest extends AbstractFrontendTestCase
{
    /**
     * The fixture news are dated 2024 and 2025, so the date menu has to list
     * both years.
     */
    #[IgnoreDeprecations]
    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function dateMenuActionRendersYears(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $html = $this->assertRendersWithoutError($this->renderPage(6));

        self::assertStringContainsString('2025', $html);
        self::assertStringContainsString('2024', $html);
    }
}
