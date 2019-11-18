<?php
namespace AlterNET\AlternetBeloginnews\Utility;

/* **************************************************************
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