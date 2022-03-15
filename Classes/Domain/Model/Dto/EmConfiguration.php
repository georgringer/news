<?php

namespace GeorgRinger\News\Domain\Model\Dto;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Extension Manager configuration
 */
class EmConfiguration
{

    /**
     * Fill the properties properly
     *
     * @param array $configuration em configuration
     */
    public function __construct(array $configuration = [])
    {
        if (empty($configuration)) {
            try {
                $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
                $configuration = $extensionConfiguration->get('news');
            } catch (\Exception $exception) {
                // do nothing
            }
        }
        foreach ($configuration as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $value;
            }
        }
    }

    /** @var int */
    protected $tagPid = 0;

    /** @var bool */
    protected $prependAtCopy = true;

    /** @var string */
    protected $categoryRestriction = '';

    /** @var bool */
    protected $categoryBeGroupTceFormsRestriction = false;

    /** @var bool */
    protected $contentElementRelation = true;

    /** @var bool */
    protected $contentElementPreview = true;

    /** @var bool */
    protected $manualSorting = false;

    /** @var string */
    protected $archiveDate = 'date';

    /** @var bool */
    protected $dateTimeNotRequired = false;

    /** @var bool */
    protected $showImporter = false;

    /** @var bool */
    protected $rteForTeaser = false;

    /** @var bool */
    protected $showAdministrationModule = true;

    /** @var bool */
    protected $hidePageTreeForAdministrationModule = false;

    /** @var int */
    protected $storageUidImporter = 1;

    /** @var string */
    protected $resourceFolderImporter = '/news_import';

    /** @var bool */
    protected $advancedMediaPreview = true;

    /** @var string */
    protected $slugBehaviour = 'unique';

    public function getTagPid(): int
    {
        return (int)$this->tagPid;
    }

    public function getPrependAtCopy(): bool
    {
        return (bool)$this->prependAtCopy;
    }

    public function getCategoryRestriction(): string
    {
        return $this->categoryRestriction;
    }

    public function getCategoryBeGroupTceFormsRestriction(): bool
    {
        return (bool)$this->categoryBeGroupTceFormsRestriction;
    }

    public function getContentElementRelation(): bool
    {
        return (bool)$this->contentElementRelation;
    }

    public function getContentElementPreview(): bool
    {
        return (bool)$this->contentElementPreview;
    }

    public function getManualSorting(): bool
    {
        return (bool)$this->manualSorting;
    }

    public function getArchiveDate(): string
    {
        return $this->archiveDate;
    }

    public function getShowImporter(): bool
    {
        return (bool)$this->showImporter;
    }

    public function setShowAdministrationModule($showAdministrationModule): void
    {
        $this->showAdministrationModule = $showAdministrationModule;
    }

    public function getShowAdministrationModule(): bool
    {
        return (bool)$this->showAdministrationModule;
    }

    public function getRteForTeaser(): bool
    {
        return (bool)$this->rteForTeaser;
    }

    public function getResourceFolderImporter(): string
    {
        return $this->resourceFolderImporter;
    }

    public function getStorageUidImporter(): int
    {
        return (int)$this->storageUidImporter;
    }

    public function getDateTimeNotRequired(): bool
    {
        return (bool)$this->dateTimeNotRequired;
    }

    public function getDateTimeRequired(): bool
    {
        return !(bool)$this->dateTimeNotRequired;
    }

    public function getHidePageTreeForAdministrationModule(): bool
    {
        return (bool)$this->hidePageTreeForAdministrationModule;
    }

    public function isAdvancedMediaPreview(): bool
    {
        return (bool)$this->advancedMediaPreview;
    }

    public function getSlugBehaviour(): string
    {
        return $this->slugBehaviour;
    }
}
