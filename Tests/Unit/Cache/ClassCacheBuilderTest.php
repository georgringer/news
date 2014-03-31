<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
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
 * Tests for Tx_News_Tests_Unit_Cache_ClassCacheBuilder
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Cache_ClassCacheBuilderTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function cacheFileCanBeWritten() {
		$classCacheBuilder = $this->getAccessibleMock('Tx_News_Cache_ClassCacheBuilder', array('dummy'));
		$content = '// this is some test';
		$identifier = 'Test/Unit/Fo';

		$writtenCacheFile = PATH_site . 'typo3temp/Cache/Code/cache_phpcode/' . $classCacheBuilder->_call('generateFileNameFromIdentifier', $identifier);

		$classCacheBuilder->_call('writeFile', $content, $identifier);

		$cacheFileContent = '<?php ' . LF . $content . LF . '}' . LF . '?>';
		$this->assertEquals($cacheFileContent, \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($writtenCacheFile));
		unlink($writtenCacheFile);
	}

	/**
	 * @test
	 * @dataProvider generatedCacheFileNameIsCorrectDataProvider
	 */
	public function generatedCacheFileNameIsCorrect($expectedResult, $given) {
		$classCacheBuilder = $this->getAccessibleMock('Tx_News_Cache_ClassCacheBuilder', array('dummy'));
		$result = $classCacheBuilder->_call('generateFileNameFromIdentifier', $given);

		$this->assertEquals($expectedResult, $result, 'exp:' . $expectedResult . ' - ' . $result);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function generatedCacheFileNameIsCorrectDataProvider() {
		return array(
			'expectedExample' => array('Fo_Bar_Blub.php', 'Fo/Bar/Blub'),
			'simpleExample' => array('Fo.php', 'Fo'),
			'simpleExampleAllLowerCase' => array('Fo.php', 'fo'),
		);
	}

	/**
	 * @test
	 * @dataProvider generatedCacheFileNameThrowsAnExceptionIfIdentifierIsWrongDataProvider
	 * @expectedException InvalidArgumentException
	 */
	public function generatedCacheFileNameThrowsAnExceptionIfIdentifierIsWrong($given) {
		$classCacheBuilder = $this->getAccessibleMock('Tx_News_Cache_ClassCacheBuilder', array('dummy'));
		$classCacheBuilder->_call('generateFileNameFromIdentifier', $given);
	}

	/**
	 * @return array
	 */
	public function generatedCacheFileNameThrowsAnExceptionIfIdentifierIsWrongDataProvider() {
		return array(
			'emptyStringGiven' => array(''),
			'NullGiven' => array(NULL),
			'ObjectGiven' => array(new SplObjectStorage()),
		);
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function parseSingleFileThrowsAnExceptionIfFileNotFound() {
		$classCacheBuilder = $this->getAccessibleMock('Tx_News_Cache_ClassCacheBuilder', array('dummy'));
		$classCacheBuilder->_call('parseSingleFile', 'someNonExistingDirectory/file.php');
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function changeCodeThrowsAnExceptionIfEmptyCodeGiven() {
		$classCacheBuilder = $this->getAccessibleMock('Tx_News_Cache_ClassCacheBuilder', array('dummy'));
		$classCacheBuilder->_call('changeCode', '', 'anyFile');
	}

	/**
	 * @test
	 * @dataProvider changeCodeWorksDataProvider
	 */
	public function changeCodeWorks($given, $expectedResult, $removeClassDefinition = TRUE) {
		$classCacheBuilder = $this->getAccessibleMock('Tx_News_Cache_ClassCacheBuilder', array('dummy'));
		$result = $classCacheBuilder->_call('changeCode', $given, 'SomeFile', $removeClassDefinition, FALSE);

		$this->assertEquals($result, $expectedResult);
	}

	public function changeCodeWorksDataProvider() {
		return array(
			'BasicFile' => array(
						$this->getClassExampleFileContent('BasicFile.php'),
						$this->getClassExampleFileContent('BasicFileResult.txt')
			),
			'BasicFileWithInitialComment' => array(
						$this->getClassExampleFileContent('BasicFileWithInitialComment.php'),
						$this->getClassExampleFileContent('BasicFileResult.txt')
			),
			'BasicFileWithNamespace' => array(
						$this->getClassExampleFileContent('BasicFileWithNamespace.php'),
						$this->getClassExampleFileContent('BasicFileResult.txt')
			),
			'BasicFileAndKeepClassDefinition' => array(
				$this->getClassExampleFileContent('BasicFileWithInitialComment.php'),
				$this->getClassExampleFileContent('BasicFileWithClassDefinitionResult.txt'),
				FALSE
			),
		);
	}

	/**
	 * Return content of a example file.
	 * Trimming is used as whitespaces can be ignored in unit tests
	 * and should not lead to errors
	 *
	 * @param string $file
	 * @return string
	 */
	protected function getClassExampleFileContent($file) {
		$exampleFileDir = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news') . 'Tests/Unit/Cache/ClassExamples/';

		$content = \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($exampleFileDir . $file);
		$content = trim($content);
		$content = str_replace("\r", '', $content);
		$content = str_replace("\n", '', $content);

		return $content;
	}

}

?>