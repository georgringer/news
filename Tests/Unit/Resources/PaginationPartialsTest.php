<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

namespace GeorgRinger\News\Tests\Unit\Resources;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\BaseTestCase;

class PaginationPartialsTest extends BaseTestCase
{
    #[DataProvider('paginationPartialsDataProvider')]
    #[Test]
    public function paginationPartialsContainAccessibilityAttributes(string $partialPath): void
    {
        $content = file_get_contents(dirname(__DIR__, 3) . '/' . $partialPath);
        self::assertIsString($content);
        self::assertStringContainsString('rel="prev"', $content);
        self::assertStringContainsString('rel="next"', $content);
        self::assertStringContainsString('aria-current="page"', $content);
    }

    public static function paginationPartialsDataProvider(): array
    {
        return [
            'default partial' => [
                'Resources/Private/Partials/List/Pagination.html',
            ],
            'twb partial' => [
                'Resources/Private/Templates/Styles/Twb/Partials/List/Pagination.html',
            ],
            'twb5 partial' => [
                'Resources/Private/Templates/Styles/Twb5/Partials/List/Pagination.html',
            ],
        ];
    }
}
