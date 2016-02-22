<?php

namespace GeorgRinger\News\Utility;

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
 * TypoScript Utility class
 */
class TypoScript
{

    /**
     * @param array $base
     * @param array $overload
     * @return array
     */
    public function override(array $base, array $overload)
    {
        $validFields = GeneralUtility::trimExplode(',', $overload['settings']['overrideFlexformSettingsIfEmpty'], true);
        foreach ($validFields as $fieldName) {

            // Multilevel field
            if (strpos($fieldName, '.') !== false) {
                $keyAsArray = explode('.', $fieldName);

                $foundInCurrentTs = $this->getValue($base, $keyAsArray);

                if (is_string($foundInCurrentTs) && strlen($foundInCurrentTs) === 0) {
                    $foundInOriginal = $this->getValue($overload['settings'], $keyAsArray);
                    if ($foundInOriginal) {
                        $base = $this->setValue($base, $keyAsArray, $foundInOriginal);
                    }
                }
            } else {
                // if flexform setting is empty and value is available in TS
                if (((!isset($base[$fieldName]) || $base[$fieldName] === '0') || (strlen($base[$fieldName]) === 0))
                    && isset($overload['settings'][$fieldName])
                ) {
                    $base[$fieldName] = $overload['settings'][$fieldName];
                }
            }
        }
        return $base;
    }

    /**
     * Get value from array by path
     *
     * @param array $data
     * @param array $path
     * @return array|null
     */
    protected function getValue(array $data, array $path)
    {
        $found = true;

        for ($x = 0; ($x < count($path) && $found); $x++) {
            $key = $path[$x];

            if (isset($data[$key])) {
                $data = $data[$key];
            } else {
                $found = false;
            }
        }

        if ($found) {
            return $data;
        }
        return null;
    }

    /**
     * Set value in array by path
     *
     * @param array $array
     * @param $path
     * @param $value
     * @return array
     */
    protected function setValue(array $array, $path, $value)
    {
        $this->setValueByReference($array, $path, $value);

        $final = array_merge_recursive([], $array);
        return $final;
    }

    /**
     * Set value by reference
     *
     * @param array $array
     * @param array $path
     * @param $value
     */
    private function setValueByReference(array &$array, array $path, $value)
    {
        while (count($path) > 1) {
            $key = array_shift($path);
            if (!isset($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }

        $key = reset($path);
        $array[$key] = $value;
    }
}
