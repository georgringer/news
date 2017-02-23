<?php

namespace GeorgRinger\News\Domain\Repository;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Repository for tt_content objects
 */
class TtContentRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    protected $objectType = '\GeorgRinger\News\Domain\Model\Ttcontent';
}
