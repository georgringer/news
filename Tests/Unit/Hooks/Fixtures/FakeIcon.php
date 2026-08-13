<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Hooks\Fixtures;

use TYPO3\CMS\Core\Imaging\Icon;

/**
 * Test double for Icon, returning a fixed markup so no icon provider setup is required.
 */
class FakeIcon extends Icon
{
    public function render(?string $alternativeMarkupIdentifier = null): string
    {
        return '<icon/>';
    }
}
