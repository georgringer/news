<?php

class Tx_News2_Domain_Model_Media extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $media;

	/**
	 * @var integer
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $html;

	/**
	 * @var string
	 */
	protected $video;

	/**
	 * @var boolean;
	 */
	protected $showinpreview;

	public function getTitle() {
	 return $this->title;
	}

	public function setTitle($title) {
	 $this->title = $title;
	}

	public function getMedia() {
	 return $this->media;
	}

	public function setMedia($media) {
	 $this->media = $media;
	}

	public function getType() {
	 return $this->type;
	}

	public function setType($type) {
	 $this->type = $type;
	}

	public function getHtml() {
	 return $this->html;
	}

	public function setHtml($html) {
	 $this->html = $html;
	}

	public function getVideo() {
	 return $this->video;
	}

	public function setVideo($video) {
	 $this->video = $video;
	}

	public function getShowinpreview() {
		return $this->showinpreview;
	}

	public function setShowinpreview($showinpreview) {
		$this->showinpreview = $showinpreview;
	}



}


?>