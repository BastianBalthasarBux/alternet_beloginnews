<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "alternet_beloginnews".
 *
 * Auto generated 05-04-2013 12:08
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Backend Login RSS news',
	'description' => 'Shows news items from an RSS feed in the backend login form.',
	'category' => 'be',
	'author' => 'alterNET Internet BV',
	'author_email' => 'support@alternet.nl',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'alterNET Internet BV',
	'version' => '7.0.1',
	'doNotLoadInFE' => 1,
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-7.9.99',
		),
		'conflicts' => array(),
		'suggests' => array(),
	),
	'autoload' => array(
		'psr-4' => array('AlterNET\\AlternetBeloginnews\\' => 'Classes')
	),
);