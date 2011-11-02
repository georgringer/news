<?php

class Tx_News_Domain_Model_AdministrationDemand extends Tx_News_Domain_Model_NewsDemand {

	/**
	 *
	 * @var string
	 */
	protected $recursive;

	public function getRecursive() {
		return $this->recursive;
	}

	public function setRecursive($recursive) {
		$this->recursive = $recursive;
	}


}
?>
