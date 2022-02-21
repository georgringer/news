<?php

declare(strict_types=1);
namespace GeorgRinger\News\Updates;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Fills sys_category.slug with a proper value
 */
class PopulateCategorySlugs implements UpgradeWizardInterface
{
    protected $table = 'sys_category';

    protected $fieldName = 'slug';

    /**
     * @return string Unique identifier of this updater
     */
    public function getIdentifier(): string
    {
        return 'sysCategorySlugs';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'Introduce URL parts ("slugs") to all sys_categories';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Slugs for sys_category records';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {
        $updateNeeded = false;
        // Check if the database table even exists
        if ($this->checkIfWizardIsRequired()) {
            $updateNeeded = true;
        }
        return $updateNeeded;
    }

    /**
     * @return string[] All new fields and tables must exist
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    /**
     * Performs the accordant updates.
     *
     * @return bool Whether everything went smoothly or not
     */
    public function executeUpdate(): bool
    {
        $this->populateSlugs();
        return true;
    }

    /**
     * Fills the database table  with slugs based on the page title and its configuration.
     *
     * @return void
     */
    protected function populateSlugs(): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->table);
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $statement = $queryBuilder
            ->select('*')
            ->from($this->table)
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq($this->fieldName, $queryBuilder->createNamedParameter('')),
                    $queryBuilder->expr()->isNull($this->fieldName)
                )
            )
            // Ensure that live workspace records are handled first
            ->addOrderBy('t3ver_wsid', 'asc')
            ->addOrderBy('pid', 'asc')
            ->addOrderBy('sorting', 'asc')
            ->execute();

        // Check for existing slugs from realurl
        $suggestedSlugs = [];

        $fieldConfig = $GLOBALS['TCA'][$this->table]['columns'][$this->fieldName]['config'];
        $evalInfo = !empty($fieldConfig['eval']) ? GeneralUtility::trimExplode(',', $fieldConfig['eval'], true) : [];
        $hasToBeUniqueInSite = in_array('uniqueInSite', $evalInfo, true);
        $hasToBeUniqueInPid = in_array('uniqueInPid', $evalInfo, true);
        $slugHelper = GeneralUtility::makeInstance(SlugHelper::class, $this->table, $this->fieldName, $fieldConfig);

        while ($record = $statement->fetch()) {
            $recordId = (int)$record['uid'];
            $pid = (int)$record['pid'];
            $languageId = (int)$record['sys_language_uid'];
            $recordInDefaultLanguage = $languageId > 0 ? (int)$record['l10n_parent'] : $recordId;
            $slug = $suggestedSlugs[$recordInDefaultLanguage][$languageId] ?? '';

            if (empty($slug)) {
                if ($pid === -1) {
                    $queryBuilder = $connection->createQueryBuilder();
                    $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
                    $liveVersion = $queryBuilder
                        ->select('pid')
                        ->from($this->table)
                        ->where(
                            $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($record['t3ver_oid'], \PDO::PARAM_INT))
                        )->execute()->fetch();
                    $pid = (int)$liveVersion['pid'];
                }
                $slug = $slugHelper->generate($record, $pid);
            }

            $state = RecordStateFactory::forName($this->table)
                ->fromArray($record, $pid, $recordId);
            if ($hasToBeUniqueInSite && !$slugHelper->isUniqueInSite($slug, $state)) {
                $slug = $slugHelper->buildSlugForUniqueInSite($slug, $state);
            }
            if ($hasToBeUniqueInPid && !$slugHelper->isUniqueInPid($slug, $state)) {
                $slug = $slugHelper->buildSlugForUniqueInPid($slug, $state);
            }

            $connection->update(
                $this->table,
                [$this->fieldName => $slug],
                ['uid' => $recordId]
            );
        }
    }

    /**
     * Check if there are record within database table with an empty "slug" field.
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected function checkIfWizardIsRequired(): bool
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable($this->table);
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        $numberOfEntries = $queryBuilder
            ->count('uid')
            ->from($this->table)
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq($this->fieldName, $queryBuilder->createNamedParameter('')),
                    $queryBuilder->expr()->isNull($this->fieldName)
                )
            )
            ->execute()
            ->fetchColumn();
        return $numberOfEntries > 0;
    }
}
