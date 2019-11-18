<?php
namespace AlterNET\AlternetBeloginnews\Task;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011-2015 alterNET internet BV <support@alternet.nl>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

use AlterNET\AlternetBeloginnews\Utility\Display;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BeLoginNews extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{

    protected $configuration;

    /**
     * Initialize
     *
     * @return void
     */
    protected function initialize()
    {
        $this->configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['alternet_beloginnews']);
        if (is_array($this->configuration)) {
            if (!isset($this->configuration['rssFeed'])) {
                return;
            }
            $this->configuration['maxItems'] = isset($this->configuration['maxItems']) ? $this->configuration['maxItems'] : 3;
            $this->configuration['sysNewsPid'] =
                isset($this->configuration['sysNewsPid']) ? $this->configuration['sysNewsPid'] : 1;
            $language = isset($this->configuration['language']) ? $this->configuration['language'] : 'en';
            $languageService = GeneralUtility::makeInstance('TYPO3\\CMS\\Lang\\LanguageService');
            $languageService->init($language);

            $title = $this->configuration['title'];

            if (trim($title)) {
                // Set title
                $loginLabels = explode('|', $GLOBALS['TYPO3_CONF_VARS']['BE']['loginLabels']);
                $loginLabels[8] = $title;
                $GLOBALS['TYPO3_CONF_VARS']['BE']['loginLabels'] = implode('|', $loginLabels);
            }

            $this->configuration['moreInfo'] = $languageService->sL('LLL:EXT:alternet_beloginnews/locallang_db.xml:more');
        }
    }

    /**
     * Execute scheduler task
     *
     * @return bool
     */
    public function execute()
    {
        $this->initialize();
        $items = Display::getRssItems($this->configuration['rssFeed']);
        $storagePid = intval($this->configuration['sysNewsPid']);
        // remove old records
        $this->getDatabaseConnection()
            ->exec_DELETEquery('sys_news',
                'pid=' . $storagePid . BackendUtility::BEenableFields('sys_news') . BackendUtility::deleteClause('sys_news'));
        $insertData = array();
        $fields = array('pid', 'tstamp', 'crdate', 'title', 'content');
        $numberOfItems = count($items);
        for ($i = 0; $i < $numberOfItems && $i < $this->configuration['maxItems']; $i++) {
            $insertData[] = array(
                $storagePid,
                strtotime($items[$i]['pubdate']),
                strtotime($items[$i]['pubdate']),
                $items[$i]['title'],
                trim(strip_tags($items[$i]['description'])) . ' <a href="' . htmlspecialchars(trim($items[$i]['link'])) . '" target="_blank">' .
                $this->configuration['moreInfo'] . '</a>'
            );
        }
        if (!empty($insertData)) {
            $this->getDatabaseConnection()->exec_INSERTmultipleRows('sys_news', $fields, $insertData);
        }

        return true;
    }

    /**
     * Wrapper for global database connection object
     *
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}