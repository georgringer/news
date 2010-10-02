<?php

class Tx_News2_Domain_Model_Category extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var Tx_News2_Domain_Model_Category
	 * @lazy
	 */
	protected $parentcategory;

	/**
	 * @var string
	 */
	protected $image;

	public function getTitle() {
	 return $this->title;
	}

	public function setTitle($title) {
	 $this->title = $title;
	}

	public function getDescription() {
	 return $this->description;
	}

	public function setDescription($description) {
	 $this->description = $description;
	}

	public function getImage() {
	 return $this->image;
	}

	public function setImage($image) {
	 $this->image = $image;
	}

	/**
	 *
	 * * @return Tx_News2_Domain_Model_Category
	 */
	public function getParentcategory() {
		if ($this->parentcategory instanceof Tx_Extbase_Persistence_LazyLoadingProxy) {
			$this->parentcategory->_loadRealInstance();
		}
	 return $this->parentcategory;
	}

	public function setParentcategory($category) {
	 $this->parentcategory = $category;
	}


}


?>