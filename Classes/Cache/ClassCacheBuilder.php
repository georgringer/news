<?php

class Tx_News_Cache_ClassCacheBuilder {

	public static function build() {
		$cacheEntries = array();

		$loadedExtensions = array_unique(t3lib_div::trimExplode(',', t3lib_extMgm::getEnabledExtensionList(), TRUE));

		$extensibleExtensions = array();
		foreach ($loadedExtensions as $extensionKey) {
			$extensionInfoFile = t3lib_extMgm::extPath($extensionKey, 'news_extended.txt');
			if (file_exists($extensionInfoFile)) {
				$info = t3lib_div::getUrl($extensionInfoFile);
				$classes = t3lib_div::trimExplode(',', $info, TRUE);
				print_R($classes);
				foreach($classes as $class) {
					$extensibleExtensions[$class][$extensionKey] = 1;
				}
			}
		}
//		$keys = array('Domain/Model/News', 'Domain/Model/Category');
//		$extensions = array('newsextended', 'newsextended2');
		foreach ($extensibleExtensions as $key => $extensionsWithThisClass) {
			$extendingClassFound = FALSE;
			$path = t3lib_extMgm::extPath('news') . 'Classes/' . $key . '.php';
			if (!is_file($path)) {
				throw new Exception('given file "' . $file . '" doesnt exist');
			}
			$code = self::parseSingleFile($path, FALSE);

			foreach ($extensionsWithThisClass as $extension => $_value) {
				$path = t3lib_extMgm::extPath($extension) . 'Classes/' . $key . '.php';
				if (is_file) {
					$extendingClassFound = TRUE;
					$code .= self::parseSingleFile($path);
				}
			}

			if ($extendingClassFound) {
				$cacheIdentifier = 'tx_news_' . strtolower(str_replace('/', '_', $key));
				$cacheEntries[$cacheIdentifier] = self::writeFile($code, $key);
			}
		}



		return $cacheEntries;
	}

	private static function writeFile($content, $identifier) {
		if (!is_dir(PATH_site . 'typo3temp/Cache/Code/class_cache/')) {
			t3lib_div::mkdir(PATH_site . 'typo3temp/Cache/Code/class_cache/');
		}

		$content = '<?php ' . LF . $content . LF . '}' . LF . '?>';

		$path = PATH_site . 'typo3temp/Cache/Code/class_cache/' . str_replace('/', '_', $identifier) . '.php';

		t3lib_div::writeFile($path, $content);
		return $path;
	}

	public static function parseSingleFile($file, $removeClassDefinition = TRUE) {
		if (!is_file) {
			throw new Exception('file not found: ' . $file);
		}
		$code = file_get_contents($file);
		$code = str_replace(array('<?php', '?>'), '', $code);

		if ($removeClassDefinition) {
			$pos = strpos($code, 'class Tx_');
			$pos2 = strpos($code, LF, $pos);

			$code = substr($code, 0, $pos) . substr($code, $pos2, strlen($code));
		}
		$code = trim($code);

		$code = '/*
					this is partial from: ' . $file . LF . '*/' . LF . $code;

		// remove last }
		// @todo make that better
		$code = substr($code, 0, -1);
		return $code;
	}

}

?>
