<?php

declare(strict_types=1);

namespace GeorgRinger\News\Event\Listener;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Search\Event\ModifyResultItemInLiveSearchEvent;
use TYPO3\CMS\Backend\Search\LiveSearch\DatabaseRecordProvider;
use TYPO3\CMS\Backend\Search\LiveSearch\ResultItem;
use TYPO3\CMS\Backend\Search\LiveSearch\ResultItemAction;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;

final class ModifyResultItemInLiveSearchEventListener
{
    protected LanguageService $languageService;

    private array $configuration = [
        'teaser' => [
            'icon' => 'actions-document',
            'skipIfEmpty' => true,
        ],
        'author' => [
            'icon' => 'actions-user',
            'skipIfEmpty' => true,
        ],
        'datetime' => [
            'icon' => 'actions-clock',
            'skipIfEmpty' => true,
        ],
        'path_segment' => [
            'icon' => 'form-url',
            'skipIfEmpty' => false,
        ],
    ];

    public function __construct(
        protected readonly IconFactory $iconFactory,
        protected readonly LanguageServiceFactory $languageServiceFactory,
        protected readonly UriBuilder $uriBuilder
    ) {
        $this->languageService = $this->languageServiceFactory->createFromUserPreferences($GLOBALS['BE_USER']);
    }

    public function __invoke(ModifyResultItemInLiveSearchEvent $event): void
    {
        $resultItem = $event->getResultItem();
        if ($resultItem->getProviderClassName() !== DatabaseRecordProvider::class) {
            return;
        }

        $table = $resultItem->getExtraData()['table'] ?? '';
        if ($table !== 'tx_news_domain_model_news') {
            return;
        }

        $row = $resultItem->getInternalData()['row'] ?? null;
        if (!$row) {
            return;
        }

        $this->addFieldsToResult($resultItem, $row);
        $this->addNotesToResult($resultItem, $row);
        $this->appendIdToTitle($resultItem, $row['uid']);
    }

    protected function addFieldsToResult(ResultItem $resultItem, array $row): void
    {
        foreach ($this->configuration as $fieldName => $field) {
            if (!isset($GLOBALS['TCA']['tx_news_domain_model_news']['columns'][$fieldName])) {
                continue;
            }
            $fieldValue = $row[$fieldName] ?? null;
            if ($fieldValue === null) {
                continue;
            }

            $label = $this->languageService->sL(BackendUtility::getItemLabel('tx_news_domain_model_news', $fieldName));
            $content = BackendUtility::getProcessedValue('tx_news_domain_model_news', $fieldName, $fieldValue, 0, false, false, $row['uid']);

            if (!$content && $field['skipIfEmpty']) {
                continue;
            }
            $action = (new ResultItemAction('tx_news_domain_model_news' . '_' . $fieldName))
                ->setLabel($content)
                ->setIcon($field['icon'] ? $this->iconFactory->getIcon($field['icon'], Icon::SIZE_SMALL) : null);
            $resultItem->addAction($action);
        }
    }

    protected function addNotesToResult(ResultItem $resultItem, array $row): void
    {
        $notesField = $GLOBALS['TCA']['tx_news_domain_model_news']['ctrl']['descriptionColumn'] ?? null;
        if ($notesField && ($row[$notesField] ?? false)) {
            $content = BackendUtility::getProcessedValue('tx_news_domain_model_news', $notesField, $row[$notesField]);

            $action = (new ResultItemAction('tx_news_domain_model_news' . '_descriptionColumn'))
                ->setLabel($content)
                ->setIcon($this->iconFactory->getIcon('actions-notebook', Icon::SIZE_SMALL));
            $resultItem->addAction($action);
        }
    }

    protected function appendIdToTitle(ResultItem $resultItem, int $id): void
    {
        $currentTitle = $resultItem->jsonSerialize()['itemTitle'] ?? false;
        if ($currentTitle) {
            $resultItem->setItemTitle(sprintf('%s [%s]', $currentTitle, $id));
        }
    }

}
