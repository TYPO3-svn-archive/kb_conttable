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
 * Hook class for the TCE Main class so we can process newly created and
 * overwritten templates and also the selection of Templates when we are
 * in User Mode
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
 *   50: class tx_t3lib_tcemain_process_datamap
 *   58:     function tx_t3lib_tcemain_process_datamap()
 *   72:     function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, $calling_obj)
 *  205:     function getDataAr(&$flexData, $flexDS, $flexDataOrig)
 *  240:     function initFlexform(&$incomingFieldArray, $id)
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('kb_conttable').'class.tx_kbconttable_funcs.php');
class tx_t3lib_tcemain_process_datamap	{
	var $flexField = 'tx_templavoila_flex';


	/**
	 * Constructor
	 *
	 * @return	void
	 */
	function tx_t3lib_tcemain_process_datamap()	{
		$this->funcs = t3lib_div::makeInstance('tx_kbconttable_funcs');
		$this->funcs->init($this);
		$this->fastMode = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['fastMode']?true:false;
	}
	
	/**
	 * This method must exist to fix a bug in T3 3.7.0. It is just dummy as it gets never called. The below method gets called instead.
	 *
	 * @param	array		Incoming field array
	 * @param	string		Table
	 * @param	integer		UID
	 * @param	object		Calling (Parent) Object
	 * @return	void
	 */
	function processDatamap_preProcessIncomingFieldArray(&$incomingFieldArray, $table, $id, $calling_obj)	{
	}

	/**
	 * Hook for pre processing the incoming fieldArray
	 *
	 * @param	array		Incoming field array
	 * @param	string		Table
	 * @param	integer		UID
	 * @param	object		Calling (Parent) Object
	 * @return	void
	 */
	function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, $calling_obj)	{
		if (($table=='tt_content')&&is_array($incomingFieldArray)&&($incomingFieldArray['CType']=='kb_conttable_pi1'))	{
			if (substr($id, 0, 3)=='NEW')	{
				$flexDS = t3lib_div::xml2array($incomingFieldArray['tx_kbconttable_flex_ds']);
				if (!is_array($flexDS))	{
					$this->initFlexform($incomingFieldArray, $id);
				}
			} else if (t3lib_div::intInRange($id, 1))	{
						// We have an UID, this is an update, check flexform fields
					$flexData = $incomingFieldArray[$this->flexField];
					$flexDS = t3lib_div::xml2array($incomingFieldArray['tx_kbconttable_flex_ds']);
					$row = t3lib_BEfunc::getRecord('tt_content', $id);
					$flexDataOrig = t3lib_div::xml2array($row[$this->flexField]);
					if (!(is_array($flexData)&&is_array($flexDS)&&is_array($flexDataOrig)))	{
							// Invalid values in Array. Initialize.
						$this->initFlexform($incomingFieldArray, $id);
					} else	{
						if ($overwrite = t3lib_div::intInRange($flexData['data']['newtemplate']['lDEF']['overwrite']['vDEF'], 0))	{
							$rec = t3lib_BEfunc::getRecord('tx_kbconttable_tmpl', $overwrite);
							if (is_array($rec))	{
									// The record to overwrite exists
									// Get data array containig the new flex-data and DS values
								$tmpAr = $this->getDataAr($flexData, $flexDS, $flexDataOrig);
								if ($tmpAr)	{
									$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_kbconttable_tmpl', 'uid='.$overwrite, $tmpAr);
								}
							}
						} else if (strlen(trim($flexData['data']['newtemplate']['lDEF']['name']['vDEF'])))	{
								// Create a new Template. This has precedence before overwrite
							$tmpAr = $this->getDataAr($flexData, $flexDS, $flexDataOrig);
							if ($tmpAr)	{
								$tmpAr['cruser_id'] = $GLOBALS['BE_USER']->user['uid'];
								$tmpAr['crdate'] = time();
								$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_kbconttable_tmpl', $tmpAr);
							}
						} else if ($select = t3lib_div::intInRange($flexData['data']['template']['lDEF']['select']['vDEF'], 0))	{
							$rec = t3lib_BEfunc::getRecord('tx_kbconttable_tmpl', $select);
							if (is_array($rec))	{
								switch ($rec['content_mode'])	{
									case '0':	// Empty
											// clear
										$flexAr = t3lib_div::xml2array($rec['flex']);
										foreach ($flexAr['data'] as $sheet => $sheetAr)	{
											if (substr($sheet, 0, strlen('s_row_')) != 's_row_') continue;
											foreach ($sheetAr['lDEF'] as $element => $elementAr)	{
												$parts = explode('_', $element, 4);
												if (($parts[0]=='column')&&($parts[3]=='elements'))	{
													$flexAr['data'][$sheet]['lDEF'][$element]['vDEF'] = '';
												}
												if (($parts[0]=='column')&&($parts[3]=='rte_content'))	{
													$flexAr['data'][$sheet]['lDEF'][$element]['vDEF'] = '';
												}
											}
										}
										$flex = t3lib_div::array2xml($flexAr, '', 0, 'T3FlexForms');
										$flexData = $flexAr;
										$vals = array(
											$this->flexField => $flex,
											'tx_kbconttable_flex_ds' => $rec['flex_ds'],
											'tstamp' => time(),
										);
										$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid='.$id, $vals);
										$flexDS = t3lib_div::xml2array($rec['flex_ds']);
										$incomingFieldArray['tx_kbconttable_flex_ds'] = $rec['flex_ds'];
									break;
									case '1':	// Copy
										$xmlhandler = t3lib_div::makeInstance('tx_kbconttable_xmlrelhndl');
										$xmlhandler->colPos = isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['colPos'])?intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['colPos']):10;;
										$flexAr = t3lib_div::xml2array($rec['flex']);
										$store = array();
										foreach ($flexAr['data'] as $sheet => $sheetAr)	{
											if (substr($sheet, 0, strlen('s_row_')) != 's_row_') continue;
											foreach ($sheetAr['lDEF'] as $element => $elementAr)	{
												$parts = explode('_', $element, 4);
												if (($parts[0]=='column')&&($parts[3]=='elements'))	{
													$store[$sheet][$element] = $flexAr['data'][$sheet]['lDEF'][$element]['vDEF'];
													$flexAr['data'][$sheet]['lDEF'][$element]['vDEF'] = '';
												}
											}
										}
										$flex = t3lib_div::array2xml($flexAr, '', 0, 'T3FlexForms');
										$vals = array(
											$this->flexField => $flex,
											'tx_kbconttable_flex_ds' => $rec['flex_ds'],
											'tstamp' => time(),
										);
										$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid='.$id, $vals);
										foreach ($store as $sheet => $sheetAr)	{
											foreach ($sheetAr as $element => $val)	{
												$elements = t3lib_div::trimExplode(',', $val, 1);
												$destination = 'tt_content:'.$id.':'.$sheet.':lDEF:'.$element.':vDEF:0';
												$elements = array_reverse($elements);
												foreach ($elements as $copyId)	{
													if (t3lib_div::testInt($copyId))	{
														$xmlhandler->pasteRecord('copy', 'tt_content|'.$copyId, $destination);
													} else	{
														$parts = explode('_', $copyId);
														$copyId = intval(array_pop($parts));
														$copyTable = implode('_', $parts);
														$xmlhandler->pasteRecord('copy', $copyTable.'|'.$copyId, $destination);
													}
												}
											}
										}
										$rec = t3lib_BEfunc::getRecord('tt_content', $id);
										$flexData = t3lib_div::xml2array($rec[$this->flexField]);
										$flexDS = t3lib_div::xml2array($rec['flex_ds']);
										$incomingFieldArray['tx_kbconttable_flex_ds'] = $rec['flex_ds'];
									break;
									case '2':	// Reference
											// Just assign
										$flex = $rec['flex'];
										$flexData = t3lib_div::xml2array($rec['flex']);
										$vals = array(
											$this->flexField => $flex,
											'tx_kbconttable_flex_ds' => $rec['flex_ds'],
											'tstamp' => time(),
										);
										$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid='.$id, $vals);
										$flexDS = t3lib_div::xml2array($rec['flex_ds']);
										$incomingFieldArray['tx_kbconttable_flex_ds'] = $rec['flex_ds'];
									break;
									case '3':	// Keep
										$newFlexData = t3lib_div::xml2array($rec['flex']);
										$flexData = $this->mergeTableStyles($flexData, $newFlexData);
										$flex = t3lib_div::array2xml($flexData, '', 0, 'T3FlexForms');
										$vals = array(
											$this->flexField => $flex,
										);
										$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid='.$id, $vals);
									break;
								}
							}
						}
						if (intval($flexData['data']['sDEF']['lDEF']['rte_mode']['vDEF'])!==intval($flexDataOrig['data']['sDEF']['lDEF']['rte_mode']['vDEF']))	{
							if (intval($flexData['data']['sDEF']['lDEF']['rte_mode']['vDEF']))	{
								$flexDS = $this->initDS_RTE($flexDS, $flexDataOrig);
							} else	{
								$flexDS = $this->removeDS_RTE($flexDS, $flexDataOrig);
							}
							$flexData = t3lib_div::array_merge_recursive_overrule($flexDataOrig, $flexData);
							$flexData = $this->funcs->setDataFields_byDS($flexDS, $flexData);
							$DS = t3lib_div::array2xml($flexDS, '', 0, 'T3DataStructure');
							$flex = t3lib_div::array2xml($flexData, '', 0, 'T3FlexForms');
							$vals = array(
								'tx_kbconttable_flex_ds' => $DS,
								$this->flexField => $flex,
								'tstamp' => time(),
							);
							$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid='.$id, $vals);
							$incomingFieldArray['tx_kbconttable_flex_ds'] = $DS;
						}
						$incomingFieldArray[$this->flexField] = $flexData;
					}
			} // } else if (t3lib_div::intInRange($id, 1))	{
		} // if (($table=='tt_content')&&is_array($incomingFieldArray)&&($incomingFieldArray['CType']=='kb_conttable_pi1'))	{
	}



	function mergeTableStyles($flexData, $newFlexData)	{
		$sDef = $flexData['data']['sDEF'];
		foreach ($flexData['data'] as $sheet => $sheetAr)	{
			if (substr($sheet, 0, strlen('s_row_')) == 's_row_') continue;
			$flexData['data'][$sheet] = $newFlexData['data'][$sheet];
		}
		$flexData['data']['sDEF']['lDEF']['rows'] = $sDef['lDEF']['rows'];
		$flexData['data']['sDEF']['lDEF']['visible_rows'] = $sDef['lDEF']['visible_rows'];
		$flexData['data']['sDEF']['lDEF']['hidden_columns'] = $sDef['lDEF']['hidden_columns'];
		return $flexData;
	}


	function initDS_RTE($flexDS, &$flexData)	{
		$rows = t3lib_div::trimExplode(',', $flexData['data']['sDEF']['lDEF']['rows']['vDEF'], 1);
		if (is_array($rows))	{
			foreach ($rows as $row)	{
				if (is_array($flexDS['sheets']['s_row_'.$row]))	{
					$columns = t3lib_div::trimExplode(',', $flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'], 1);
					if (is_array($columns))	{
						foreach ($columns as $col)	{
							$xml = $this->funcs->defaultCellDS_rte();
							$xml = str_replace('###ROW###', $row, $xml);
							$xml = str_replace('###COLUMN###', $col, $xml);
							$xmlAr = t3lib_div::xml2array($xml);
							$xmlAr = $this->funcs->columnLabel($xmlAr, $col);
							$flexDS['sheets']['s_row_'.$row]['ROOT']['el'] = array_merge($flexDS['sheets']['s_row_'.$row]['ROOT']['el'], $xmlAr);
						}
					}
				}
			}
		}
		return $flexDS;
	}

	function removeDS_RTE($flexDS, &$flexData)	{
		$rows = t3lib_div::trimExplode(',', $flexData['data']['sDEF']['lDEF']['rows']['vDEF'], 1);
		if (is_array($rows))	{
			foreach ($rows as $row)	{
				if (is_array($flexDS['sheets']['s_row_'.$row]))	{
					$columns = t3lib_div::trimExplode(',', $flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'], 1);
					if (is_array($columns))	{
						foreach ($columns as $col)	{
							unset($flexDS['sheets']['s_row_'.$row]['ROOT']['el']['column_'.$row.'_'.$col.'_rte_content']);
						}
					}
				}
			}
		}
		return $flexDS;
	}

	/**
	 * Return data array for a new/modified template
	 *
	 * @param	array		Flexform data (passed by reference)
	 * @param	array		Flexform DS (passed by reference)
	 * @param	integer		Unmodified Flexform data array
	 * @return	array		data array for new/modified template
	 */
	function getDataAr(&$flexData, $flexDS, $flexDataOrig)	{
		$ret = array();
		$ret['name'] = $flexData['data']['newtemplate']['lDEF']['name']['vDEF'];
		$ret['tsconfig_name'] = str_replace(' ', '', trim($flexData['data']['newtemplate']['lDEF']['tsconfig_name']['vDEF']));
		if (!strlen($ret['tsconfig_name'])) return false;
		$ret['allowed_users'] = $flexData['data']['newtemplate']['lDEF']['allowed_users']['vDEF'];
		$ret['allowed_groups'] = $flexData['data']['newtemplate']['lDEF']['allowed_groups']['vDEF'];
		$ret['content_mode'] = $flexData['data']['newtemplate']['lDEF']['content_mode']['vDEF'];
		$ret['pid'] = $flexData['data']['newtemplate']['lDEF']['storage_folder']['vDEF'];
		$ret['tstamp'] = time();
		$ret['deleted'] = 0;
		$ret['hidden'] = 0;
		$this->funcs->findingStorageFolderIds();
		if (!t3lib_div::inList($this->funcs->storageFolders_pidList, $ret['pid'])) return false;
		if (!intval($ret['pid'])) return false;
		$flexData['data']['newtemplate']['lDEF']['name']['vDEF'] = '';
		$flexData['data']['newtemplate']['lDEF']['tsconfig_name']['vDEF'] = '';
		$flexData['data']['newtemplate']['lDEF']['overwrite']['vDEF'] = 0;
		$flexData['data']['newtemplate']['lDEF']['allowed_users']['vDEF'] = '';
		$flexData['data']['newtemplate']['lDEF']['allowed_groups']['vDEF'] = '';
		$flexData['data']['newtemplate']['lDEF']['content_mode']['vDEF'] = 0;
		$flexData['data']['newtemplate']['lDEF']['storage_folder']['vDEF'] = 0;
		$flexData = t3lib_div::array_merge_recursive_overrule($flexDataOrig, $flexData);
		$ret['flex'] = t3lib_div::array2xml($flexData, '', 0, 'T3FlexForms');
		$ret['flex_ds'] = t3lib_div::array2xml($flexDS, '', 0, 'T3DataStructure');
		return $ret;
	}

	/**
	 * Initializes a KB Content Table element
	 *
	 * @param	array		Incoming field array
	 * @param	integer		UID
	 * @return	void
	 */
	function initFlexform(&$incomingFieldArray, $id)	{
		$flexDS = $this->funcs->defaultFlexDS();
		$flexDS = t3lib_div::xml2array($flexDS);
		$flexData = array();
		$flexDS = $this->funcs->getDefaultTable_DataDS($flexDS, $flexData);
		$flexData = $this->funcs->setDataFields_byDS($flexDS, $flexData);
		$incomingFieldArray[$this->flexField] = $flexData;
		$incomingFieldArray['tx_kbconttable_flex_ds'] = t3lib_div::array2xml($flexDS, '', 0, 'T3DataStructure');
	}


}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_t3lib_tcemain.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_t3lib_tcemain.php']);
}


?>
