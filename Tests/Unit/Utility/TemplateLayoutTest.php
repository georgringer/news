<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Utility;

use GeorgRinger\News\Utility\TemplateLayout;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * TemplateLayout utility class unit tests
 */
class TemplateLayoutTest extends BaseTestCase
{
    #[Test]
    public function templatesFoundInPageTsConfig(): void
    {
        $tsConfigArray = [
            'layout1' => 'Layout 1',
            'layout2' => 'Layout 2',
        ];
        $result = [
            'layout1' => [
                'label' => 'Layout 1',
                'key' => 'layout1',
            ],
            'layout2' => [
                'label' => 'Layout 2',
                'key' => 'layout2',
            ],
        ];

        $templateLayoutUtility = $this->getAccessibleMock(TemplateLayout::class, ['getTemplateLayoutsFromTsConfig']);
        $templateLayoutUtility->expects(self::once())->method('getTemplateLayoutsFromTsConfig')->willReturn($tsConfigArray);
        $templateLayouts = $templateLayoutUtility->_call('getAvailableTemplateLayouts', 1);
        self::assertSame($result, $templateLayouts);
    }
}
