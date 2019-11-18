<?php
namespace AlterNET\AlternetBeloginnews\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Display BE login news
 */
class Display
{
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
