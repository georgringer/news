<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Frontend;

use PHPUnit\Framework\Attributes\Test;

class NewsListTest extends AbstractFrontendTestCase
{
    #[Test]
    public function listActionRendersNewsTitles(): void
    {
        $this->setUpFrontend();

        $html = $this->assertRendersWithoutError($this->renderPage(2));

        self::assertStringContainsString('Rendering Test News One', $html);
        self::assertStringContainsString('Rendering Test News Two', $html);
    }
}
