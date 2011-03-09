<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
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
 * Tests for DisqusSizeViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News2_Tests_Unit_ViewHelpers_Format_DisqusViewHelperTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @var Tx_Phpunit_Framework
	 */
	protected $testingFramework;

	public function setUp() {
		$this->testingFramework = new Tx_Phpunit_Framework('tx_news2');
	}

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperReturnsCorrectJs() {
		$newsRepository = $this->objectManager->get('Tx_News2_Domain_Repository_NewsRepository');

		$newUid = $this->testingFramework->createRecord(
			'tx_news2_domain_model_news', array('pid' => 98));
		$newsItem = $newsRepository->findByUid($newUid);

		$viewHelper = new Tx_News2_ViewHelpers_Social_DisqusViewHelper();
		$actualResult = $viewHelper->render($newsItem, 'abcdef', 'http://typo3.org/dummy/fobar.html');

		$expectedCode = '<script type="text/javascript">
					var disqus_shortname = "abcdef";
					var disqus_identifier = "news_' . $newUid . '";
					var disqus_url = "' . htmlspecialchars('http://typo3.org/dummy/fobar.html') . '";

					(function() {
						var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
						dsq.src = "http://" + disqus_shortname + ".disqus.com/embed.js";
						(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
					})();
				</script>';

		$this->assertEquals($expectedCode, $actualResult);
	}

	/**
	 * Tear down and remove records
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->testingFramework->cleanUp();
		unset($this->testingFramework);
	}

}
?>
