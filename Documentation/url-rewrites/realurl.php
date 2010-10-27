<?php

/**
* @author Georg Ringer <georg.ringer@cyberhouse.at>
*/

// starting a nice documentation

$postvars = array(
	'news' => array(
		array(
			'GETvar' => 'tx_news2_pi1[news]',
			'lookUpTable' => array(
				'table' => 'tx_news2_domain_model_news',
				'id_field' => 'uid',
				'alias_field' => 'title',
				'addWhereClause' => ' AND NOT deleted',
				'useUniqueCache' => 1,
				'useUniqueCache_conf' => array(
					'strtolower' => 1,
					'spaceCharacter' => '-',
				),
			),
		),
		array(
			'GETvar' => 'tx_news2_pi1[action]',
		),
		array(
			'GETvar' => 'tx_news2_pi1[controller]',
		),
	),

);
?>
