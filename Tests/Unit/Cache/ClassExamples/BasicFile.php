<?php

class Tx_Fo_Domain_Model_News {

	/**
	 * Some fo
	 *
	 * @var array
	 */
	protected $fo = array();

	/**
	 * @param array $fo
	 */
	public function setFo($fo) {
		$this->fo = $fo;
	}

	/**
	 * @return array
	 */
	public function getFo() {
		return $this->fo;
	}


}
