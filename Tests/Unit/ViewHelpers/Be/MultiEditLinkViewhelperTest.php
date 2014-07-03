<?php
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

/**
 * Tests for Tx_News_ViewHelpers_Be_MultiEditLinkViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_ViewHelpers_Be_MultiEditLinkViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperReturnsCorrectJavaScriptLink() {
		$viewHelper = new Tx_News_ViewHelpers_Be_MultiEditLinkViewHelper();

		$newsItem1 = new Tx_News_Domain_Model_News();
		$newsItem1->setTitle('Item 1');
		$newsItem1->_setProperty('uid', 3);
		$newsItem2 = new Tx_News_Domain_Model_News();
		$newsItem2->setTitle('Item 2');
		$newsItem2->_setProperty('uid', 9);

		$newsItems = new SplObjectStorage();
		$newsItems->attach($newsItem1);
		$newsItems->attach($newsItem2);

		$columns = 'title,description';
		$actualResult = $viewHelper->render($newsItems, $columns);

		$content = 'window.location.href=\'alt_doc.php?returnUrl=\'+T3_THIS_LOCATION+\'&edit[tx_news_domain_model_news][' .
			'3,9' .
			']=edit&columnsOnly=title,description&disHelp=1\';return false;';

		$this->assertEquals($content, $actualResult);
	}


}
