<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2012 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class Cache builder
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Cache_ClassCacheBuilder {

	const CACHE_FILE_LOCATION = 'typo3temp/Cache/Code/cache_phpcode/';

	/**
	 * Builds the proxy files
	 *
	 * @return array information for the autoloader
	 * @throws Exception
	 */
	public function build() {
		$this->init();
		$cacheEntries = array();

		$extensibleExtensions = $this->getExtensibleExtensions();

		foreach ($extensibleExtensions as $key => $extensionsWithThisClass) {
			$extendingClassFound = FALSE;

			// Get the file from news itself, this needs to be loaded as first
			$path = t3lib_extMgm::extPath('news') . 'Classes/' . $key . '.php';
			if (!is_file($path)) {
				throw new Exception('given file "' . $path . '" does not exist');
			}
			$code = $this->parseSingleFile($path, FALSE);

			// Get the files from all other extensions
			foreach ($extensionsWithThisClass as $extension => $value) {
				$path = t3lib_extMgm::extPath($extension) . 'Classes/' . $key . '.php';
				if (is_file($path)) {
					$extendingClassFound = TRUE;
					$code .= $this->parseSingleFile($path);
				}
			}

			// If an extending class is found, the file is written and
			// added to the autoloader info
			if ($extendingClassFound) {
				$cacheIdentifier = 'tx_news_' . strtolower(str_replace('/', '_', $key));
				try {
					$cacheEntries[$cacheIdentifier] = $this->writeFile($code, $key);
				} catch (Exception $e) {
					throw new Exception($e->getMessage());
				}
			}
		}

		return $cacheEntries;
	}

	/**
	 * Get all loaded extensions which try to extend EXT:news
	 *
	 * @return array
	 */
	protected function getExtensibleExtensions() {
		$loadedExtensions = array_unique(t3lib_div::trimExplode(',', Tx_News_Utility_Compatibility::getLoadedExtensionList(), TRUE));

		// Get the extensions which want to extend news
		$extensibleExtensions = array();
		foreach ($loadedExtensions as $extensionKey) {
			$extensionInfoFile = t3lib_extMgm::extPath($extensionKey, 'Resources/Private/extend-news.txt');
			if (file_exists($extensionInfoFile)) {
				$info = t3lib_div::getUrl($extensionInfoFile);
				$classes = t3lib_div::trimExplode(LF, $info, TRUE);
				foreach ($classes as $class) {
					$extensibleExtensions[$class][$extensionKey] = 1;
				}
			}
		}
		return $extensibleExtensions;
	}

	/**
	 * Load some classes
	 *
	 * @return void
	 */
	protected function init() {
		$t3libUtilityVersionNumber = PATH_t3lib . 'utility/class.t3lib_utility_versionnumber.php';
		if (@is_file($t3libUtilityVersionNumber)) {
			require_once($t3libUtilityVersionNumber);
		} else {
			require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('core') . 'Classes/Utility/VersionNumberUtility.php';
		}
		require_once(t3lib_extMgm::extPath('news', 'Classes/Utility/Compatibility.php'));
	}

	/**
	 * Write the proxy file
	 *
	 * @param string $content
	 * @param string $identifier identifier of the file
	 * @throws RuntimeException
	 * @return string path of the written file
	 */
	protected function writeFile($content, $identifier) {
		$path = PATH_site . self::CACHE_FILE_LOCATION;
		if (!is_dir($path)) {
			t3lib_div::mkdir_deep(PATH_site, self::CACHE_FILE_LOCATION);
		}

		$content = '<?php ' . LF . $content . LF . '}' . LF . '?>';

		$path .= $this->generateFileNameFromIdentifier($identifier);

		$success = t3lib_div::writeFile($path, $content);
		if (!$success) {
			throw new RuntimeException('File "' . $path . '" could not be written');
		}
		return $path;
	}

	/**
	 * Generate cache file name
	 *
	 * @param string $identifier identifier
	 * @return string
	 * @throws InvalidArgumentException
	 */
	protected function generateFileNameFromIdentifier($identifier) {
		if (!is_string($identifier) || empty($identifier)) {
			throw new InvalidArgumentException('Given identifier is either not a string or empty');
		}

		$result = str_replace('/', '_', $identifier) . '.php';
		$result = ucfirst($result);

		return $result;
	}

	/**
	 * Parse a single file and does some magic
	 * - Remove the <?php tags
	 * - Remove the class definition (if set)
	 *
	 * @param string $filePath path of the file
	 * @param boolean $removeClassDefinition If class definition should be removed
	 * @return string path of the saved file
	 * @throws Exception
	 * @throws InvalidArgumentException
	 */
	public function parseSingleFile($filePath, $removeClassDefinition = TRUE) {
		if (!is_file($filePath)) {
			throw new InvalidArgumentException(sprintf('File "%s" could not be found', $filePath));
		}
		$code = t3lib_div::getUrl($filePath);
		return $this->changeCode($code, $filePath, $removeClassDefinition);
	}

	/**
	 * @param string $code
	 * @param string $filePath
	 * @param boolean $removeClassDefinition
	 * @param boolean $renderPartialInfo
	 * @return string
	 * @throws Exception
	 * @throws InvalidArgumentException
	 */
	protected function changeCode($code, $filePath, $removeClassDefinition = TRUE, $renderPartialInfo = TRUE) {
		if (empty($code)) {
			throw new InvalidArgumentException(sprintf('File "%s" could not be fetched or is empty', $filePath));
		}
		$code = trim($code);
		$code = str_replace(array('<?php', '?>'), '', $code);
		$code = trim($code);

		// Remove everything before 'class Tx_', including namespaces,
		// comments and require-statements.
		if ($removeClassDefinition) {
			$pos = strpos($code, 'class Tx_');
			$pos2 = strpos($code, '{', $pos);

			$code = substr($code, $pos2 + 1);
		}

		$code = trim($code);

		// Add some information for each partial
		if ($renderPartialInfo) {
			$code = $this->getPartialInfo($filePath) . $code;
		}

		// Remove last }
		$pos = strrpos($code, '}');
		$code = substr($code, 0, $pos);
		$code = trim($code);
		return $code;
	}

	protected function getPartialInfo($filePath) {
		return LF . LF . '/*' . str_repeat('*', 70) . LF . TAB .
			'this is partial from: ' . $filePath . LF . str_repeat('*', 70) . '*/' . LF;
	}

}

?>