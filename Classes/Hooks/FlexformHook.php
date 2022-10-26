<?php

namespace GeorgRinger\News\Hooks;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into \TYPO3\CMS\Backend\Utility\BackendUtility to change flexform behaviour
 * depending on view
 */
class FlexformHook
{

    /** @var EmConfiguration */
    protected $configuration;

    /**
     * BackendUtility constructor.
     * @param EmConfiguration $configuration
     */
    public function __construct(
    ) {
        $this->configuration = GeneralUtility::makeInstance(EmConfiguration::class);
    }

    /**
     * @param array $dataStructure
     * @param array $identifier
     *
     * @return array|string
     */
    public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier)
    {
        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === 'news_pi1,list') {
            $getVars = GeneralUtility::_GET('edit');
            if (isset($getVars['tt_content']) && is_array($getVars['tt_content'])) {
                $item = array_keys($getVars['tt_content']);
                $recordId = (int)$item[0];

                if (($getVars['tt_content'][$recordId] ?? '') === 'new') {
                    $fakeRow = [
                        'uid' => 'NEW123'
                    ];
                    $this->updateFlexforms($dataStructure, $fakeRow);
                } else {
                    $row = BackendUtilityCore::getRecord('tt_content', $recordId);
                    if (is_array($row)) {
                        $this->updateFlexforms($dataStructure, $row);
                    }
                }
            }
        }
        return $dataStructure;
    }

    /**
     * Update flexform configuration if a action is selected
     *
     * @param array|string &$dataStructure flexform structure
     * @param array $row row of current record
     * @param array $dataStructure
     *
     * @return void
     */
    protected function updateFlexforms(array &$dataStructure, array $row): void
    {
        $selectedView = '';
        $flexformSelection = [];
        if (isset($row['pi_flexform'])) {
            // get the first selected action
            if (is_string($row['pi_flexform'])) {
                $flexformSelection = GeneralUtility::xml2array($row['pi_flexform']);
            } else {
                $flexformSelection = $row['pi_flexform'];
            }
        }
        if (is_array($flexformSelection) && isset($flexformSelection['data'])) {
            $selectedView = $flexformSelection['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'] ?? '';
            if (!empty($selectedView)) {
                $actionParts = GeneralUtility::trimExplode(';', $selectedView, true);
                $selectedView = $actionParts[0];
            }

            // new plugin element
        } elseif (str_starts_with((string)$row['uid'], 'NEW')) {
            // use List as starting view
            $selectedView = 'News->list';
        }

        if (!empty($selectedView)) {

            if ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Hooks/BackendUtility.php']['updateFlexforms'] ?? []) {
                $params = [
                    'selectedView' => $selectedView,
                    'dataStructure' => &$dataStructure,
                ];
                foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Hooks/BackendUtility.php']['updateFlexforms'] as $reference) {
                    GeneralUtility::callUserFunction($reference, $params, $this);
                }
            }
        }
    }
}
