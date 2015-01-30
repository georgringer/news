<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

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
use GeorgRinger\News\Domain\Model\Media;

/**
 * Tests for domains model Media
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class MediaTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var Media
	 */
	protected $mediaDomainModelInstance;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->mediaDomainModelInstance = new Media();
	}

	/**
	 * Test if title can be set
	 *
	 * @test
	 * @return void
	 */
	public function titleCanBeSet() {
		$title = 'File title';
		$this->mediaDomainModelInstance->setTitle($title);
		$this->assertEquals($title, $this->mediaDomainModelInstance->getTitle());
	}

	/**
	 * Test if crdate can be set
	 *
	 * @test
	 * @return void
	 */
	public function crdateCanBeSet() {
		$time = new \DateTime('now');
		$this->mediaDomainModelInstance->setCrdate($time);
		$this->assertEquals($time, $this->mediaDomainModelInstance->getCrdate());
	}

	/**
	 * Test if tstamp can be set
	 *
	 * @test
	 * @return void
	 */
	public function tstampCanBeSet() {
		$time = new \DateTime('now');
		$this->mediaDomainModelInstance->setTstamp($time);
		$this->assertEquals($time, $this->mediaDomainModelInstance->getTstamp());
	}

	/**
	 * Test if hidden can be set
	 *
	 * @test
	 * @return void
	 */
	public function hiddenCanBeSet() {
		$flag = 1;
		$this->mediaDomainModelInstance->setHidden($flag);
		$this->assertEquals($flag, $this->mediaDomainModelInstance->getHidden());
	}

	/**
	 * Test if deleted can be set
	 *
	 * @test
	 * @return void
	 */
	public function deletedCanBeSet() {
		$flag = 1;
		$this->mediaDomainModelInstance->setDeleted($flag);
		$this->assertEquals($flag, $this->mediaDomainModelInstance->getDeleted());
	}

	/**
	 * Test if showinpreview can be set
	 *
	 * @test
	 * @return void
	 */
	public function showinpreviewCanBeSet() {
		$flag = 1;
		$this->mediaDomainModelInstance->setShowinpreview($flag);
		$this->assertEquals($flag, $this->mediaDomainModelInstance->getShowinpreview());
	}

	/**
	 * Test if caption can be set
	 *
	 * @test
	 * @return void
	 */
	public function captionCanBeSet() {
		$caption = 'title';
		$this->mediaDomainModelInstance->setCaption($caption);
		$this->assertEquals($caption, $this->mediaDomainModelInstance->getCaption());
	}

	/**
	 * Test if alt can be set
	 *
	 * @test
	 * @return void
	 */
	public function altCanBeSet() {
		$alt = 'Fobar text';
		$this->mediaDomainModelInstance->setAlt($alt);
		$this->assertEquals($alt, $this->mediaDomainModelInstance->getAlt());
	}

	/**
	 * Test if type can be set
	 *
	 * @test
	 * @return void
	 */
	public function typeCanBeSet() {
		$type = 3;
		$this->mediaDomainModelInstance->setType($type);
		$this->assertEquals($type, $this->mediaDomainModelInstance->getType());
	}

	/**
	 * Test if width can be set
	 *
	 * @test
	 * @return void
	 */
	public function widthCanBeSet() {
		$width = 100;
		$this->mediaDomainModelInstance->setWidth($width);
		$this->assertEquals($width, $this->mediaDomainModelInstance->getWidth());
	}

	/**
	 * Test if height can be set
	 *
	 * @test
	 * @return void
	 */
	public function heightCanBeSet() {
		$height = 80;
		$this->mediaDomainModelInstance->setHeight($height);
		$this->assertEquals($height, $this->mediaDomainModelInstance->getHeight());
	}

	/**
	 * Test if sorting can be set
	 *
	 * @test
	 * @return void
	 */
	public function sortingCanBeSet() {
		$sorting = 12345678;
		$this->mediaDomainModelInstance->setSorting($sorting);
		$this->assertEquals($sorting, $this->mediaDomainModelInstance->getSorting());
	}

	/**
	 * Test if image can be set
	 *
	 * @test
	 * @return void
	 */
	public function imageCanBeSet() {
		$image = 'fo.jpg';
		$this->mediaDomainModelInstance->setImage($image);
		$this->assertEquals($image, $this->mediaDomainModelInstance->getImage());
	}

	/**
	 * Test if video can be set
	 *
	 * @test
	 * @return void
	 */
	public function multimediaCanBeSet() {
		$multimedia = 'http://youtube.com/123';
		$this->mediaDomainModelInstance->setMultimedia($multimedia);
		$this->assertEquals($multimedia, $this->mediaDomainModelInstance->getMultimedia());
	}

	/**
	 * Test if copyright can be set
	 *
	 * @test
	 * @return void
	 */
	public function copyrightCanBeSet() {
		$copyright = 'by Creative Commons';
		$this->mediaDomainModelInstance->setCopyright($copyright);
		$this->assertEquals($copyright, $this->mediaDomainModelInstance->getCopyright());
	}

	/**
	 * Test if description can be set
	 *
	 * @test
	 * @return void
	 */
	public function descriptionCanBeSet() {
		$description = 'Some words';
		$this->mediaDomainModelInstance->setDescription($description);
		$this->assertEquals($description, $this->mediaDomainModelInstance->getDescription());
	}
}
