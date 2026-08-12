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

class NewsDetailTest extends AbstractFrontendTestCase
{
    #[IgnoreDeprecations]
    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function detailActionRendersNewsContent(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $html = $this->assertRendersWithoutError($this->renderPage(3));

        self::assertStringContainsString('Rendering Test News One', $html);
        self::assertStringContainsString('Bodytext of news one', $html);
        self::assertStringContainsString('Test Author', $html);
    }

    /**
     * Der Teaser wird im Default-Template ueber ein anderes Feld gerendert
     * als in den Twb-Templates - genau dort ist der Drift entstanden.
     */
    #[IgnoreDeprecations]
    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function detailActionRendersTeaser(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $html = $this->assertRendersWithoutError($this->renderPage(3));

        self::assertStringContainsString('Teaser of news one', $html);
    }

    #[IgnoreDeprecations]
    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function detailActionRendersCategoryAndTag(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $html = $this->assertRendersWithoutError($this->renderPage(3));

        self::assertStringContainsString('Rendering Test Tag', $html);
    }

    #[IgnoreDeprecations]
    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function detailActionRendersMediaCaption(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $html = $this->assertRendersWithoutError($this->renderPage(3));

        self::assertStringContainsString('Caption of the test image', $html);
    }

    #[IgnoreDeprecations]
    #[Test]
    #[DataProvider('templateStyleProvider')]
    public function detailActionRendersRelatedLink(string $stylePath): void
    {
        $this->setUpFrontend($stylePath);

        $html = $this->assertRendersWithoutError($this->renderPage(3));

        self::assertStringContainsString('Rendering Test Link', $html);
        self::assertStringContainsString('Description of the related link', $html);
    }
}
