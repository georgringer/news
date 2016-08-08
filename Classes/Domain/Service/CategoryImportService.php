<?php

namespace GeorgRinger\News\Domain\Service;

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
use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Category Import Service
 *
 */
class CategoryImportService extends AbstractImportService
{

    const ACTION_SET_PARENT_CATEGORY = 1;
    const ACTION_CREATE_L10N_CHILDREN_CATEGORY = 2;

    /**
     * @var \GeorgRinger\News\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     */
    protected $signalSlotDispatcher;

    public function __construct()
    {
        $logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        $this->logger = $logger;

        parent::__construct();
    }

    /**
     * Inject the category repository.
     *
     * @param \GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository
     * @return void
     */
    public function injectCategoryRepository(\GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Inject SignalSlotDispatcher
     *
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
     * @return void
     */
    public function injectSignalSlotDispatcher(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher)
    {
        $this->signalSlotDispatcher = $signalSlotDispatcher;
    }

    /**
     * @param array $importArray
     * @return void
     */
    public function import(array $importArray)
    {
        $this->logger->info(sprintf('Starting import for %s categories', count($importArray)));

        // Sort import array to import the default language first
        foreach ($importArray as $importItem) {
            $category = $this->hydrateCategory($importItem);

            if (!empty($importItem['title_lang_ol'])) {
                $this->postPersistQueue[$importItem['import_id']] = [
                    'category' => $category,
                    'importItem' => $importItem,
                    'action' => self::ACTION_CREATE_L10N_CHILDREN_CATEGORY,
                    'titleLanguageOverlay' => $importItem['title_lang_ol']
                ];
            }

            if ($importItem['parentcategory']) {
                $this->postPersistQueue[$importItem['import_id']] = [
                    'category' => $category,
                    'action' => self::ACTION_SET_PARENT_CATEGORY,
                    'parentCategoryOriginUid' => $importItem['parentcategory']
                ];
            }
        }

        $this->persistenceManager->persistAll();

        foreach ($this->postPersistQueue as $queueItem) {
            switch ($queueItem['action']) {
                case self::ACTION_SET_PARENT_CATEGORY:
                    $this->setParentCategory($queueItem);
                    break;
                case self::ACTION_CREATE_L10N_CHILDREN_CATEGORY:
                    $this->createL10nChildrenCategory($queueItem);
                    break;
                default:
                    // do nothing
                    break;
            }
        }

        $this->persistenceManager->persistAll();
    }

    /**
     * Hydrate a category record with the given array
     *
     * @param array $importItem
     * @return Category
     */
    protected function hydrateCategory(array $importItem)
    {
        $category = $this->categoryRepository->findOneByImportSourceAndImportId($importItem['import_source'],
            $importItem['import_id']);

        $this->logger->info(sprintf('Import of category from source "%s" with id "%s"', $importItem['import_source'],
            $importItem['import_id']));

        if (is_null($category)) {
            $this->logger->info('Category is new');

            $category = $this->objectManager->get(\GeorgRinger\News\Domain\Model\Category::class);
            $this->categoryRepository->add($category);
        } else {
            $this->logger->info(sprintf('Category exists already with id "%s".', $category->getUid()));
        }

        $category->setPid($importItem['pid']);
        $category->setHidden($importItem['hidden']);
        $category->setStarttime($importItem['starttime']);
        $category->setEndtime($importItem['endtime']);
        $category->setCrdate($importItem['crdate']);
        $category->setTstamp($importItem['tstamp']);
        $category->setTitle($importItem['title']);
        $category->setDescription($importItem['description']);
        if (!empty($importItem['image'])) {
            $this->setFileRelationFromImage($category, $importItem['image']);
        }
        $category->setShortcut($importItem['shortcut']);
        $category->setSinglePid($importItem['single_pid']);

        $category->setImportId($importItem['import_id']);
        $category->setImportSource($importItem['import_source']);

        $arguments = ['importItem' => $importItem, 'category' => $category];
        $this->emitSignal('postHydrate', $arguments);

        return $category;
    }

    /**
     * Add category image when not already present
     *
     * @param Category $category
     * @param $image
     */
    protected function setFileRelationFromImage($category, $image)
    {

        // get fileObject by given identifier (file UID, combined identifier or path/filename)
        try {
            $newImage = $this->getResourceFactory()->retrieveFileOrFolderObject($image);
        } catch (\TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException $exception) {
            $newImage = false;
        }

        // only proceed if image is found
        if (!$newImage instanceof \TYPO3\CMS\Core\Resource\File) {
            return;
        }

        // new image found check if this isn't already
        $existingImages = $category->getImages();
        if (!is_null($existingImages) && $existingImages->count() !== 0) {
            /** @var $item FileReference */
            foreach ($existingImages as $item) {
                // only check already persisted items
                if ($item->getFileUid() === (int)$newImage->getUid()
                    ||
                    ($item->getUid() &&
                        $item->getOriginalResource()->getName() === $newImage->getName() &&
                        $item->getOriginalResource()->getSize() === (int)$newImage->getSize())
                ) {
                    $newImage = false;
                    break;
                }
            }
        }

        if ($newImage) {
            // file not inside a storage then search for existing file or copy the one form storage 0 to the import folder
            if ($newImage->getStorage()->getUid() === 0) {

                // search DB for same file based on hash (to prevent duplicates)
                $existingFile = $this->findFileByHash($newImage->getSha1());

                // no exciting file then copy file to import folder
                if ($existingFile === null) {
                    $newImage = $this->getResourceStorage()->copyFile($newImage, $this->getImportFolder());
                } else {
                    $newImage = $existingFile;
                }
            }

            $fileReference = $this->objectManager->get(\GeorgRinger\News\Domain\Model\FileReference::class);
            $fileReference->setFileUid($newImage->getUid());
            $fileReference->setPid($category->getPid());
            $category->addImage($fileReference);
        }
    }

    /**
     * Set parent category
     *
     * @param array $queueItem
     * @return void
     */
    protected function setParentCategory(array $queueItem)
    {
        /** @var $category Category */
        $category = $queueItem['category'];
        $parentCategoryOriginUid = $queueItem['parentCategoryOriginUid'];

        if (is_null($parentCategory = $this->postPersistQueue[$parentCategoryOriginUid]['category'])) {
            $parentCategory = $this->categoryRepository->findOneByImportSourceAndImportId(
                $category->getImportSource(),
                $parentCategoryOriginUid
            );
        }

        if ($parentCategory !== null) {
            $category->setParentcategory($parentCategory);
            $this->categoryRepository->update($category);
        }
    }

    /**
     * Create l10n relation
     *
     * @param array $queueItem
     * @return void
     */
    protected function createL10nChildrenCategory(array $queueItem)
    {
        /** @var $category Category */
        $category = $queueItem['category'];
        $titleLanguageOverlay = GeneralUtility::trimExplode('|', $queueItem['titleLanguageOverlay']);

        foreach ($titleLanguageOverlay as $key => $title) {
            $sysLanguageUid = $key + 1;

            $importItem = $queueItem['importItem'];
            $importItem['import_id'] = $importItem['import_id'] . '|L:' . $sysLanguageUid;

            /** @var $l10nChildrenCategory Category */
            $l10nChildrenCategory = $this->hydrateCategory($importItem);
            $this->categoryRepository->add($l10nChildrenCategory);

            $l10nChildrenCategory->setTitle($title);
            $l10nChildrenCategory->setL10nParent((int)$category->getUid());
            $l10nChildrenCategory->setSysLanguageUid((int)$sysLanguageUid);
        }
    }

    /**
     * Emits signal
     *
     * @param string $signalName name of the signal slot
     * @param array $signalArguments arguments for the signal slot
     */
    protected function emitSignal($signalName, array $signalArguments)
    {
        $this->signalSlotDispatcher->dispatch('GeorgRinger\\News\\Domain\\Service\\CategoryImportService', $signalName,
            $signalArguments);
    }
}
