<?php
namespace AlterNET\AlternetBeloginnews\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Display BE login news
 */
class Display
{

    /**
     * Sets title of BE login news block for TYPO3 4.5 and later
     *
     * @static
     * @return void
     */
    public static function displayNewsTitle()
    {
        $configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['alternet_beloginnews']);
        if (is_array($configuration)) {
            if (!isset($configuration['rssFeed'])) {
                return;
            }
            $title = $configuration['title'];
            if (trim($title)) {
                $GLOBALS['TYPO3_CONF_VARS']['BE']['loginNewsTitle'] = $title;
            }
        }
    }

    /**
     * Retrieves RSS feed
     *
     * @static
     * @param  $rssFeed
     * @return array List of items in the RSS feed
     */
    public static function getRssItems($rssFeed)
    {
        $parser = GeneralUtility::makeInstance('AlterNET\\AlternetBeloginnews\\Utility\\Parser');
        $xml = GeneralUtility::getUrl($rssFeed);
        $parser->parse($xml);
        $result = $parser->get_items();

        return $result;
    }

}
