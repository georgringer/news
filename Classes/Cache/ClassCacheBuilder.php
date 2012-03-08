<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Georg Ringer <typo3@ringerge.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Class Cache builder
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Cache_ClassCacheBuilder {

	/**
	 * Builds the proxy files
	 *
	 * @return array information for the autoloader
	 */
	public static function build() {
		$cacheEntries = array();

		$loadedExtensions = array_unique(t3lib_div::trimExplode(',', t3lib_extMgm::getEnabledExtensionList(), TRUE));

			// Get the extensions which want to extend news
		$extensibleExtensions = array();
		foreach ($loadedExtensions as $extensionKey) {
			$extensionInfoFile = t3lib_extMgm::extPath($extensionKey, 'Resources/Private/extend-news.txt');
			if (file_exists($extensionInfoFile)) {
				$info = t3lib_div::getUrl($extensionInfoFile);
				$classes = t3lib_div::trimExplode(LF, $info, TRUE);
				foreach($classes as $class) {
					$extensibleExtensions[$class][$extensionKey] = 1;
				}
			}
		}

		foreach ($extensibleExtensions as $key => $extensionsWithThisClass) {
			$extendingClassFound = FALSE;

				// Get the file from news itself, this needs to be loaded as first
			$path = t3lib_extMgm::extPath('news') . 'Classes/' . $key . '.php';
			if (!is_file($path)) {
				throw new Exception('given file "' . $path . '" doesnt exist');
			}
			$code = self::parseSingleFile($path, FALSE);

				// Get the files from all other extensions
			foreach ($extensionsWithThisClass as $extension => $_value) {
				$path = t3lib_extMgm::extPath($extension) . 'Classes/' . $key . '.php';
				if (is_file($path)) {
					$extendingClassFound = TRUE;
					$code .= self::parseSingleFile($path);
				}
			}

				// If an extending class is found, the file is written and
				// added to the autoloader info
			if ($extendingClassFound) {
				$cacheIdentifier = 'tx_news_' . strtolower(str_replace('/', '_', $key));
				try {
					$cacheEntries[$cacheIdentifier] = self::writeFile($code, $key);
				} catch (Exception $e) {
					throw new Exception($e->getMessage());
				}
			}
		}

		return $cacheEntries;
	}

	/**
	 * Write the proxy file
	 *
	 * @param string $content
	 * @param string $identifier identifier of the file
	 * @return path of the written file
	 */
	private static function writeFile($content, $identifier) {
		if (!is_dir(PATH_site . 'typo3temp/Cache/Code/cache_phpcode/')) {
			t3lib_div::mkdir_deep(PATH_site . 'typo3temp/Cache/Code/cache_phpcode/');
		}

		$content = '<?php ' . LF . $content . LF . '}' . LF . '?>';

		$path = PATH_site . 'typo3temp/Cache/Code/cache_phpcode/' . str_replace('/', '_', $identifier) . '.php';

		$success = t3lib_div::writeFile($path, $content);
		if (!$success) {
			throw new Exception ('file "' . $path . '" could not be written');
		}
		return $path;
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
	 */
	public static function parseSingleFile($filePath, $removeClassDefinition = TRUE) {
		if (!is_file) {
			throw new Exception(sprintf('File "%s" could not be found', $filePath));
		}
		$code = t3lib_div::getUrl($filePath);
		if (empty($code)) {
			throw new Exception(sprintf('File "%s" could not be fetched or is empty', $filePath));
		}
		$code = str_replace(array('<?php', '?>'), '', $code);

		if ($removeClassDefinition) {
			$pos = strpos($code, 'class Tx_');
			$pos2 = strpos($code, LF, $pos);

			$code = substr($code, 0, $pos) . substr($code, $pos2, strlen($code));
		}
		$code = trim($code);

			// Add some information for each partial
		$code =  LF . LF . chr(10) . chr(10) . '/*' . str_repeat('*', 70) . LF . TAB .
					'this is partial from: ' . $filePath . LF . str_repeat('*', 70) . '*/' . LF . $code;

			// Remove last }
		$pos = strrpos($code, '}');
		$code = substr($code, 0, $pos);
		$code = trim($code);
		return $code;
	}

}

?>