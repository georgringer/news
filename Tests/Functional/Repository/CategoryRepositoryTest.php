<?php

namespace GeorgRinger\News\Tests\Unit\Functional\Repository;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Functional test for the DataHandler
 */
class CategoryRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{

    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;

    /** @var  \GeorgRinger\News\Domain\Repository\CategoryRepository */
    protected $categoryRepository;

    protected $testExtensionsToLoad = ['typo3conf/ext/news'];

    public function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->categoryRepository = $this->objectManager->get('GeorgRinger\\News\\Domain\\Repository\\CategoryRepository');

        $this->importDataSet(__DIR__ . '/../Fixtures/sys_category.xml');
    }

    /**
     * Test if by import source is done
     *
     * @test
     * @return void
     */
    public function findRecordByImportSource()
    {
        $category = $this->categoryRepository->findOneByImportSourceAndImportId('functional_test', '2');

        $this->assertEquals($category->getTitle(), 'findRecordByImportSource');
    }
}
