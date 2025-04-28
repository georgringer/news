<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Functional\ViewHelpers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class ExtensionLoadedViewHelperTest extends FunctionalTestCase
{
    public static function extensionLoadedDataProvider(): array
    {
        return [
            'validExtension' => [
                '<n:extensionLoaded extensionKey="core">1</n:extensionLoaded>',
                '1',
            ],
            'invalidExtension' => [
                '<n:extensionLoaded extensionKey="something">1</n:extensionLoaded>',
                '',
            ],
        ];
    }

    #[DataProvider('extensionLoadedDataProvider')]
    #[IgnoreDeprecations]
    #[Test]
    public function extensionLoaded(string $src, string $expected): void
    {
        $src = '<html xmlns:n="http://typo3.org/ns/GeorgRinger/News/ViewHelpers" data-namespace-typo3-fluid="true">' . $src . '</html>';
        $context = $this->get(RenderingContextFactory::class)->create();
        $context->getTemplatePaths()->setTemplateSource($src);
        self::assertSame($expected, (string)(new TemplateView($context))->render());
    }
}
