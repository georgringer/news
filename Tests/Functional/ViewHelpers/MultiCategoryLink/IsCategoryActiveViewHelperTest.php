<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\ViewHelpers\MultiCategoryLink;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class IsCategoryActiveViewHelperTest extends FunctionalTestCase
{
    public static function isCategoryActiveDataProvider(): array
    {
        return [
            'item is in list' => [
                '<n:multiCategoryLink.isCategoryActive list="1,2,3" item="2">active</n:multiCategoryLink.isCategoryActive>',
                'active',
            ],
            /*
             * https://github.com/georgringer/news/issues/2803: with no else
             * child renderElseChild() returns null, which used to hit the
             * string return type of the render() override.
             */
            'item is not in list and no else child' => [
                '<n:multiCategoryLink.isCategoryActive list="1,2,3" item="4">active</n:multiCategoryLink.isCategoryActive>',
                '',
            ],
            'list is empty and no else child' => [
                '<n:multiCategoryLink.isCategoryActive list="" item="1">active</n:multiCategoryLink.isCategoryActive>',
                '',
            ],
            'item is not in list and else child is rendered' => [
                '<n:multiCategoryLink.isCategoryActive list="1,2,3" item="4"><f:then>active</f:then><f:else>inactive</f:else></n:multiCategoryLink.isCategoryActive>',
                'inactive',
            ],
        ];
    }

    #[DataProvider('isCategoryActiveDataProvider')]
    #[Test]
    public function isCategoryActive(string $src, string $expected): void
    {
        $src = '<html xmlns:n="http://typo3.org/ns/GeorgRinger/News/ViewHelpers" data-namespace-typo3-fluid="true">' . $src . '</html>';
        $context = $this->get(RenderingContextFactory::class)->create();
        $context->getTemplatePaths()->setTemplateSource($src);
        self::assertSame($expected, (string)(new TemplateView($context))->render());
    }
}
