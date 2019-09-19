<?php

namespace GeorgRinger\News\Domain\Model\Dto;

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
    public function __construct(array $configuration)
    {
        foreach ($configuration as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @var int
     */
    protected $tagPid = 0;

    /**
     * @var boolean;
     */
    protected $prependAtCopy = true;

    /**
     * @var string;
     */
    protected $categoryRestriction = '';

    /**
     * @var bool
     */
    protected $categoryBeGroupTceFormsRestriction = false;

    /**
     * @var bool
     */
    protected $contentElementRelation = true;

    /** @var bool */
    protected $contentElementPreview = true;

    /**
     * @var bool
     */
    protected $manualSorting = false;

    /**
     * @var string
     */
    protected $archiveDate = 'date';

    /**
     * @var bool
     */
    protected $dateTimeNotRequired = false;

    /**
     * @var bool
     */
    protected $showImporter = false;

    /** @var bool */
    protected $rteForTeaser = false;

    /**
     * @var bool
     */
    protected $showAdministrationModule = true;

    /** @var bool */
    protected $hidePageTreeForAdministrationModule = false;

    /**
     * @var int
     */
    protected $storageUidImporter = 1;

    /**
     * @var string
     */
    protected $resourceFolderImporter = '/news_import';

    /** @var bool */
    protected $mediaPreview = false;

    /** @var bool */
    protected $advancedMediaPreview = true;

    /**
     * @return int
     */
    public function getTagPid(): int
    {
        return (int)$this->tagPid;
    }

    /**
     *
     * @return bool
     */
    public function getPrependAtCopy()
    {
        return (bool)$this->prependAtCopy;
    }

    /**
     * @return string
     */
    public function getCategoryRestriction()
    {
        return $this->categoryRestriction;
    }

    /**
     * Get categoryBeGroupTceFormsRestriction
     *
     * @return bool
     */
    public function getCategoryBeGroupTceFormsRestriction()
    {
        return (bool)$this->categoryBeGroupTceFormsRestriction;
    }

    /**
     * @return bool
     */
    public function getContentElementRelation(): bool
    {
        return (bool)$this->contentElementRelation;
    }

    /**
     * @return bool
     */
    public function getContentElementPreview(): bool
    {
        return (bool)$this->contentElementPreview;
    }

    /**
     * @return bool
     */
    public function getManualSorting(): bool
    {
        return (bool)$this->manualSorting;
    }

    /**
     * @return string
     */
    public function getArchiveDate()
    {
        return $this->archiveDate;
    }

    /**
     * @return bool
     */
    public function getShowImporter(): bool
    {
        return (boolean)$this->showImporter;
    }

    /**
     * @param bool $showAdministrationModule
     */
    public function setShowAdministrationModule($showAdministrationModule)
    {
        $this->showAdministrationModule = $showAdministrationModule;
    }

    /**
     * @return bool
     */
    public function getShowAdministrationModule(): bool
    {
        return (bool)$this->showAdministrationModule;
    }

    /**
     * @return bool
     */
    public function getRteForTeaser(): bool
    {
        return (bool)$this->rteForTeaser;
    }

    /**
     * @return string
     */
    public function getResourceFolderImporter()
    {
        return $this->resourceFolderImporter;
    }

    /**
     * @return int
     */
    public function getStorageUidImporter(): int
    {
        return (int)$this->storageUidImporter;
    }

    /**
     * @return bool
     */
    public function getDateTimeNotRequired(): bool
    {
        return (bool)$this->dateTimeNotRequired;
    }

    /**
     * @return bool
     */
    public function getDateTimeRequired(): bool
    {
        return !(bool)$this->dateTimeNotRequired;
    }

    /**
     * @return bool
     */
    public function getHidePageTreeForAdministrationModule(): bool
    {
        return (bool)$this->hidePageTreeForAdministrationModule;
    }

    /**
     * @return bool
     */
    public function isMediaPreview(): bool
    {
        return (bool)$this->mediaPreview;
    }

    /**
     * @return bool
     */
    public function isAdvancedMediaPreview(): bool
    {
        return (bool)$this->advancedMediaPreview;
    }
}
