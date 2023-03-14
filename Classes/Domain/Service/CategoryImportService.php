<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Domain\Service;

use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Repository\CategoryRepository;
use GeorgRinger\News\Event\CategoryImportPostHydrateEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class CategoryImportService extends AbstractImportService
{
    public const ACTION_SET_PARENT_CATEGORY = 1;
    public const ACTION_CREATE_L10N_CHILDREN_CATEGORY = 2;

    /**
     * CategoryImportService constructor.
     */
    public function __construct(
        PersistenceManager $persistenceManager,
        CategoryRepository $categoryRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($persistenceManager, $categoryRepository, $eventDispatcher);
    }

    public function import(array $importArray): void
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
                    'titleLanguageOverlay' => $importItem['title_lang_ol'],
                ];
            }

            if ($importItem['parentcategory'] ?? false) {
                $this->postPersistQueue[$importItem['import_id']] = [
                    'category' => $category,
                    'action' => self::ACTION_SET_PARENT_CATEGORY,
                    'parentCategoryOriginUid' => $importItem['parentcategory'],
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
     */
    protected function hydrateCategory(array $importItem): Category
    {
        $category = $this->categoryRepository->findOneByImportSourceAndImportId(
            $importItem['import_source'],
            $importItem['import_id']
        );

        $this->logger->info(sprintf(
            'Import of category from source "%s" with id "%s"',
            $importItem['import_source'],
            $importItem['import_id']
        ));

        if ($category === null) {
            $this->logger->info('Category is new');

            $category = GeneralUtility::makeInstance(Category::class);
            $this->categoryRepository->add($category);
        } else {
            $this->logger->info(sprintf('Category exists already with id "%s".', $category->getUid()));
        }

        $category->setPid($importItem['pid']);
        $category->setHidden((bool)($importItem['hidden'] ?? false));

        if (
            isset($importItem['starttime'])
            && $date = $this->convertTimestampToDateTime((int)$importItem['starttime'])
        ) {
            $category->setStarttime($date);
        }
        if (
            isset($importItem['endtime'])
            && $date = $this->convertTimestampToDateTime((int)$importItem['endtime'])
        ) {
            $category->setEndtime($date);
        }
        if (
            isset($importItem['crdate'])
            && $date = $this->convertTimestampToDateTime((int)$importItem['crdate'])
        ) {
            $category->setCrdate($date);
        }
        if (
            isset($importItem['tstamp'])
            && $date = $this->convertTimestampToDateTime((int)$importItem['tstamp'])
        ) {
            $category->setTstamp($date);
        }
        $category->setTitle($importItem['title']);
        $category->setDescription($importItem['description']);
        if (!empty($importItem['image'])) {
            $this->setFileRelationFromImage($category, (string)$importItem['image']);
        }
        $category->setShortcut((int)($importItem['shortcut'] ?? 0));
        $category->setSinglePid((int)($importItem['single_pid'] ?? 0));

        $category->setImportId((string)($importItem['import_id'] ?? ''));
        $category->setImportSource((string)($importItem['import_source'] ?? ''));

        $event = $this->eventDispatcher->dispatch(new CategoryImportPostHydrateEvent($this, $importItem, $category));

        return $event->getCategory();
    }

    /**
     * Add category image when not already present
     */
    protected function setFileRelationFromImage(Category $category, string $image): void
    {
        // get fileObject by given identifier (file UID, combined identifier or path/filename)
        try {
            $newImage = $this->getResourceFactory()->retrieveFileOrFolderObject($image);
        } catch (ResourceDoesNotExistException $exception) {
            $newImage = false;
        }

        // only proceed if image is found
        if (!$newImage instanceof File) {
            return;
        }

        // new image found check if this isn't already
        $existingImages = $category->getImages();
        if ($existingImages->count() !== 0) {
            foreach ($existingImages as $item) {
                // only check already persisted items
                if (
                    $item->getFileUid() === $newImage->getUid()
                    || (
                        $item->getUid()
                        && $item->getOriginalResource()->getName() === $newImage->getName()
                        && $item->getOriginalResource()->getSize() === (int)$newImage->getSize()
                    )
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

            $fileReference = GeneralUtility::makeInstance(FileReference::class);
            $fileReference->setFileUid($newImage->getUid());
            $fileReference->setPid($category->getPid());
            $category->addImage($fileReference);
        }
    }

    protected function setParentCategory(array $queueItem): void
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
     */
    protected function createL10nChildrenCategory(array $queueItem): void
    {
        /** @var $category Category */
        $category = $queueItem['category'];
        $titleLanguageOverlay = GeneralUtility::trimExplode('|', $queueItem['titleLanguageOverlay']);

        foreach ($titleLanguageOverlay as $key => $title) {
            $sysLanguageUid = $key + 1;

            $importItem = $queueItem['importItem'];
            $importItem['import_id'] .= '|L:' . $sysLanguageUid;

            $l10nChildrenCategory = $this->hydrateCategory($importItem);
            $this->categoryRepository->add($l10nChildrenCategory);

            $l10nChildrenCategory->setTitle($title);
            $l10nChildrenCategory->setL10nParent((int)$category->getUid());
            $l10nChildrenCategory->setSysLanguageUid((int)$sysLanguageUid);
        }
    }
}
