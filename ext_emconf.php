<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Backend Login RSS news',
    'description' => 'Shows news items from an RSS feed in the backend login form.',
    'category' => 'be',
    'author' => 'Clemens Riccabona',
    'author_company' => 'Riccabona eSolutions',
    'author_email' => 'https://www.Riccabona.IT/',
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
    'version' => '8.0.0',
    'doNotLoadInFE' => 1,
    'constraints' => array(
        'depends' => array(
            'typo3' => '8.6.29-9.5.99',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
    'autoload' => array(
        'psr-4' => array('AlterNET\\AlternetBeloginnews\\' => 'Classes')
    ),
);
