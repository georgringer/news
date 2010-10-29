<?php
/**
 * @author Georg Ringer <typo3@ringerge.org>
 */

class Tx_News2_Domain_Model_NewsTest extends Tx_Extbase_BaseTestCase {

	/**
	 * @test
	 */
	public function titleCanBeSet() {
		$title = 'News title';
		$newsItem = new Tx_News2_Domain_Model_News();
		$newsItem->setTitle($title);

		$this->assertEquals($title, $newsItem->getTitle());
	}
}
?>
