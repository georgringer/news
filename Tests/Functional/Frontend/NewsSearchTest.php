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

class NewsSearchTest extends AbstractFrontendTestCase
{
    /**
     * On TYPO3 v13 the SearchFormViewHelper deliberately uses
     * registerTagAttribute(), which is gone in Fluid v5. That BC path is
     * guarded by a version switch, so the deprecation is expected here.
     */
    #[Test]
    #[IgnoreDeprecations]
    #[DataProvider('templateStyleProvider')]
    public function searchFormActionRendersInputField(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $html = $this->assertRendersWithoutError($this->renderPage(7));

        self::assertStringContainsString('tx_news_pi1[search][subject]', $html);
    }

    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function searchResultActionRendersMatchingNews(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $response = $this->renderPage(8, [
            'tx_news_pi1[search][subject]' => 'news one',
        ]);
        $html = $this->assertRendersWithoutError($response);

        self::assertStringContainsString('Rendering Test News One', $html);
    }
}
