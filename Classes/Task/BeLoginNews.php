<?php
namespace AlterNET\AlternetBeloginnews\Task;

use AlterNET\AlternetBeloginnews\Utility\Display;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;

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
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_news');
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        
        $affectedRows = $queryBuilder
            ->delete('sys_news')
            ->where(
                $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($storagePid))
            )
            ->execute();
        
        // insert new records
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $databaseConnectionForSysNews = $connectionPool->getConnectionForTable('sys_news');
        $numberOfItems = count($items);
        for ($i = 0; $i < $numberOfItems && $i < $this->configuration['maxItems']; $i++) {
            $insertData = [
                "pid" => $storagePid,
                "tstamp" => strtotime($items[$i]['pubdate']),
                "crdate" => strtotime($items[$i]['pubdate']),
                "title" => $items[$i]['title'],
                "content" => trim(strip_tags($items[$i]['description'])) . '<br>' . trim($items[$i]['link']) . '<br>',
            ];
            $databaseConnectionForSysNews->insert(
                'sys_news',
                $insertData
            );
        }
        return true;
    }
}
