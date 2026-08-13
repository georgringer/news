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
use TYPO3\CMS\Core\Imaging\IconFactory;

/**
 * IconFactory is declared "readonly" and therefore cannot be doubled by PHPUnit 10
 * (used in the TYPO3 v13 test matrix). On top of that, its getIcon() signature differs
 * between v13 and v14. This lightweight real subclass avoids both problems: it overrides
 * getIcon() with widened (untyped) parameters — compatible with every parent signature —
 * and deliberately skips the parent constructor to stay independent of its
 * version-specific dependency list.
 */
readonly class FakeIconFactory extends IconFactory
{
    public function __construct() {}

    public function getIcon($identifier = '', $size = null, $overlayIdentifier = null, $state = null): Icon
    {
        return new FakeIcon();
    }
}
