<?php

namespace GeorgRinger\News\Tests\Unit\Controller;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Controller\NewsController;
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Class NewsControllerTest
 */
class NewsControllerTest extends UnitTestCase
{
    /**
     * @test
     */
    public function emptyNoNewsFoundConfigurationReturnsNull()
    {
        $demand = new NewsDemand();
        $input = ['OrderByAllowed' => 'something'];
        $mockedController = $this->getAccessibleMock(NewsController::class, ['dummy']);
        /** @var NewsDemand $result */
        $result = $mockedController->_call('overwriteDemandObject', $demand, $input);
        $this->assertEmpty($result->getOrderByAllowed());
    }
}
