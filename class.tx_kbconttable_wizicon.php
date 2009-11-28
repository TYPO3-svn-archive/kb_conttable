<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Kraft Bernhard (kraftb@kraftb.at)
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
***************************************************************/
/**
 * Class for adding our new Content Element to the New Content Element wizard
 *
 * $Id$
 *
 * @author	Kraft Bernhard <kraftb@kraftb.at>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   44: class tx_kbconttable_wizicon
 *   52:     function proc($wizardItems)
 *   89:     function includeLocalLang()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_kbconttable_wizicon	{

	/**
	 * Process the list of Content Elements and replace Table element by Content Table element
	 *
	 * @param	array		wizard items
	 * @return	array		modified wizard items
	 */
	function proc($wizardItems)	{
		global $LANG;
		$cnt = 0;

		$LL = $this->includeLocalLang();

		$res = array();
		foreach ($wizardItems as $key => $item)	{
			if ($key=='special')	{
				$res['kb_conttable'] = array(
					'icon' => t3lib_extMgm::extRelPath('kb_conttable').'res/new_el_icon.gif',
					'title' => $LANG->getLLL('tt_content.CType_pi1', $LL),
					'description' => $LANG->getLLL('tt_content.CType_pi1.description', $LL),
					'params' => '&defVals[tt_content][CType]=kb_conttable_pi1',
					'tt_content_defValues' => array(
						'CType' => 'kb_conttable_pi1',
					),
				);
			} 
			$res[$key] = $item;
		}

		return $res;
	}

	/**
	 * Include locallang file for the tt_guest book extension (containing the description and title for the element)
	 *
	 * @return	array		LOCAL_LANG array
	 */
	function includeLocalLang()	{
		include(t3lib_extMgm::extPath('kb_conttable').'locallang_db.php');
		return $LOCAL_LANG;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_wizicon.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_wizicon.php']);
}

?>
