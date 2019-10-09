<?php
declare(strict_types=1);
namespace GeorgRinger\News\Compatibility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A display condition that returns true if the page containing the record we
 * are dealing with is in a page tree that is represented by a PseudoSite object.
 *
 * This condition is essentially a composition of
 * \TYPO3\CMS\Core\Compatibility\PseudoSiteTcaDisplayCondition
 * that uses the pid of a record being contained in a page.
 *
 * It also catches possible exceptions when looking for the site object up,
 * thus avoiding errors when editing sys_category or tx_news_domain_model_tag
 * records.
 *
 * Attention: T
 *
 * @see \TYPO3\CMS\Core\Compatibility\PseudoSiteTcaDisplayCondition
 *
 * Both "Pseudo sites" and "alias" db field will bite the dust in TYPO3 v10.0,
 * so this is a temporary display condition for v9 only and thus marked internal.
 *
 * @internal Implementation and class will probably vanish in TYPO3 v10.0 without further notice
 */
class PseudoSiteTcaDisplayCondition
{
    /**
     * Takes the page uid containing the given record and verifies if that
     * page has a pseudo site object or a site object attached.
     *
     * @param array $parameters
     * @return bool
     * @throws \InvalidArgumentException
     *
     * @see \TYPO3\CMS\Core\Compatibility\PseudoSiteTcaDisplayCondition::isInPseudoSite()
     */
    public function isRecordParentPageInPseudoSite(array $parameters): bool
    {
        if (
            !isset($parameters['conditionParameters'][0], $parameters['conditionParameters'][1])
            // First parameter must not be "pages" to differentiate from original class and use case.
            || $parameters['conditionParameters'][0] === 'pages'
            || !in_array($parameters['conditionParameters'][1], ['true', 'false'], true)
            || empty($parameters['record']['pid'])
        ) {
            // Argument validation failed.
            throw new \InvalidArgumentException(
                'Invalid arguments using isRecordParentPageInPseudoSite display condition',
                1570551092
            );
        }

        $parentPageRecord = BackendUtility::getRecord('pages', (int)$parameters['record']['pid']);

        if (!is_array($parentPageRecord)) {
            // Fail fast: return false value if asked for true and vice versa.
            return $parameters['conditionParameters'][1] === 'true';
        }

        // Condition won't be fulfilled if an exception is raised.
        $conditionFulfilled = false;

        try {
            $parametersForPage = [
                'record' => $parentPageRecord,
                'conditionParameters' => [
                    'pages', // First parameter must be "pages" in this case.
                    $parameters['conditionParameters'][1]
                ],
            ];
            $conditionFulfilled = (bool)GeneralUtility::callUserFunction(
                // Display condition class for pages.
                'TYPO3\\CMS\\Core\\Compatibility\\PseudoSiteTcaDisplayCondition->isInPseudoSite',
                $parametersForPage,
                $this
            );
        } catch (SiteNotFoundException $e) {
            // PseudoSite could not be found and internal class threw an exception because of it.
        } catch (\InvalidArgumentException $e) {
            // Internal class "PseudoSiteTcaDisplayCondition" doesn't exist anymore.
        }

        // Invert return value if asked for false.
        return $parameters['conditionParameters'][1] === 'true'
            ? $conditionFulfilled
            : !$conditionFulfilled;
    }
}
