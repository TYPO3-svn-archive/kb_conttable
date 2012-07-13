<?php
/***************************************************************
*  Copyright notice

*  (c) 2004-2012 Bernhard Kraft (kraftb@seicht.co.at)
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
 * Implementing Hook in class t3lib_befunc.php and XCLASS if necessary.
 *
 * $Id$
 *
 * @author	Bernhard Kraft <kraftb@seicht.co.at>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   53: class tx_t3lib_befunc_getFlexFormDS
 *   68:     function getFlexFormDS_postProcessDS(&$dsArray, $conf, &$row, $table)
 *  199:     function checkFields(&$flexData, &$newDSArray, $val)
 *  323:     function checkRows(&$flexData, &$newDSArray, $val)
 *  354:     function checkSimpleTextField(&$flexData, &$newDSArray, $val, $val_sheet, $lDEF, $vDEF, $field)
 *  382:     function checkSimpleSelectField(&$flexData, &$newDSArray, $val, $val_sheet, $lDEF, $vDEF, $field)
 *  411:     function checkSimpleIntField(&$flexData, &$newDSArray, $val, $val_sheet, $lDEF, $vDEF, $field)
 *  439:     function checkSimpleCheckField(&$flexData, &$newDSArray, $val, $val_sheet, $lDEF, $vDEF, $field)
 *
 * TOTAL FUNCTIONS: 7
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


require_once(t3lib_extMgm::extPath('kb_conttable').'class.tx_kbconttable_funcs.php');

class tx_t3lib_befunc_getFlexFormDS	{
	var $flexField = 'tx_kbconttable_flex';
	var $flexDSfield = 'tx_kbconttable_flex_ds';


	/**
	 * Hook method for post processing the Flexform DS when retrieved. Restricts
	 * some fields when a normal user is logged in.
	 *
	 * @param	array		Flexform DS Arra
	 * @param	array		TCA Configuration
	 * @param	array		Data row
	 * @param	string		Table which gets processed
	 * @return	void
	 */
	function getFlexFormDS_postProcessDS(&$dsArray, $conf, &$row, $table)	{
		global $BE_USER;
		if (!$this->funcs)	{
			$this->funcs = t3lib_div::makeInstance('tx_kbconttable_funcs');
		}
		if (($table=='tt_content')&&($conf['ds_tableField']=='tt_content:'.$this->flexDSfield)&&$row['CType']=='kb_conttable_pi1')	{
			$TSconfig = t3lib_BEfunc::getPagesTSconfig($row['pid']);
			$like_admin = 0;
			$like_admin |= t3lib_div::inList($TSconfig['tx_kbconttable.']['like_admins_user'], $BE_USER->user['uid'])?1:0;
			$like_admin |= t3lib_div::inList(trim($TSconfig['tx_kbconttable.']['like_admins_user']), trim($BE_USER->user['username']))?1:0;
			$member = $BE_USER->userGroupsUID;
			$like_admin |= count(array_intersect($member, t3lib_div::trimExplode(',', $TSconfig['tx_kbconttable.']['like_admins_group'], 1)))?1:0;
			;
			$this->restrict = ($BE_USER->user['admin']||$like_admin)?0:1;
			$modified_flexData = false;
				// We have a kb_conttable field and are not an admin: Restrict fields
			if (!$row[$this->flexField]) {
				$tcemainHook = t3lib_div::makeInstance('tx_t3lib_tcemain_process_datamap');
				$fieldArray = array();
				$tcemainHook->initFlexform($fieldArray, 0);
				$dsArray = t3lib_div::xml2array($fieldArray[$this->flexDSfield]);
				return true;
			} else {
				$flexData = t3lib_div::xml2array($row[$this->flexField]);
				if (!is_array($flexData))	{
					return false;
				}
			}
			$newDSArray = $dsArray;
				// Don't show Locking sheets at all.
			if (!is_array($newDSArray)) {
				$newDSArray = array();
			}
			if (!is_array($newDSArray['sheets'])) {
				$newDSArray['sheets'] = array();
			}
			if (is_array($newDSArray['sheets']['table_locking'])&&$this->restrict)	{
				unset($newDSArray['sheets']['table_locking']);
			}
			if (is_array($newDSArray['sheets']['cell_locking'])&&$this->restrict)	{
				unset($newDSArray['sheets']['cell_locking']);
			}
			if (is_array($newDSArray['sheets']['action_locking'])&&$this->restrict)	{
				unset($newDSArray['sheets']['action_locking']);
			}
			if (is_array($newDSArray['sheets']['newtemplate'])&&$this->restrict)	{
				unset($newDSArray['sheets']['newtemplate']);
			}
			$this->funcs->findingStorageFolderIds();
			$folders = $this->funcs->getStorageFolders();
			$templates = $this->funcs->getExistingTemplates($folders);
			if (!$this->restrict)	{
					// Get Storage Folders
					// Add options in "New Template" sheet for "Storage Folder"
				$cnt = 1;
				foreach ($folders as $folder)	{
					$newDSArray['sheets']['newtemplate']['ROOT']['el']['storage_folder']['TCEforms']['config']['items'][$cnt] = array(0 => t3lib_BEfunc::getRecordTitle('pages', $folder).' ('.$folder['uid'].')', 1 => $folder['uid']);
					$cnt++;
				}
					// Add options in "New Template" sheet for "Overwrite Existing"
				$cnt = 1;
				foreach ($templates as $template)	{
					$newDSArray['sheets']['newtemplate']['ROOT']['el']['overwrite']['TCEforms']['config']['items'][$cnt] = array(0 => $template['_label'], 1 => $template['uid']);
					$cnt++;
				}
			}
				// Add options in "Template" sheet for "Select Template"
			$cnt = 1;
			foreach ($templates as $template)	{
					$allowed = 0;
					if (!$this->restrict)	{
						$allowed = 1;
					} else	{
						$allowed_groups = t3lib_div::trimExplode(',', $template['allowed_groups'], 1);
						$inter = array_intersect($member, $allowed_groups);
						if (count($inter))	{
							$allowed = 1;
						} else	{
							if (t3lib_div::inList($template['allowed_users'], $BE_USER->user['uid']))	{
								$allowed = 1;
							}
						}
					}
					if ($allowed)	{
						$newDSArray['sheets']['template']['ROOT']['el']['select']['TCEforms']['config']['items'][$cnt] = array(0 => $template['_label'], 1 => $template['uid']);
						$cnt++;
					}
			}
			if (intval($flexData['data']['table_locking']['lDEF']['table_settings']['vDEF'])&&$this->restrict)	{
				if (is_array($newDSArray['sheets']['sDEF']))	{
					unset($newDSArray['sheets']['sDEF']);
				}
			} else	{
				$modified_flexData |= $this->checkSimpleCheckField($flexData, $newDSArray, intval($flexData['data']['table_locking']['lDEF']['rte_mode']['vDEF']), 'sDEF', 'lDEF', 'vDEF', 'rte_mode');
				$modified_flexData |= $this->checkSimpleIntField($flexData, $newDSArray, intval($flexData['data']['table_locking']['lDEF']['cellspacing']['vDEF']), 'sDEF', 'lDEF', 'vDEF', 'cellspacing');
				$modified_flexData |= $this->checkSimpleIntField($flexData, $newDSArray, intval($flexData['data']['table_locking']['lDEF']['cellpadding']['vDEF']), 'sDEF', 'lDEF', 'vDEF', 'cellpadding');
				$modified_flexData |= $this->checkSimpleIntField($flexData, $newDSArray, intval($flexData['data']['table_locking']['lDEF']['border']['vDEF']), 'sDEF', 'lDEF', 'vDEF', 'border');
				$modified_flexData |= $this->checkSimpleIntField($flexData, $newDSArray, intval($flexData['data']['table_locking']['lDEF']['width']['vDEF']), 'sDEF', 'lDEF', 'vDEF', 'width');
				$modified_flexData |= $this->checkSimpleSelectField($flexData, $newDSArray, $flexData['data']['table_locking']['lDEF']['width_format']['vDEF'], 'sDEF', 'lDEF', 'vDEF', 'width_format');
				$modified_flexData |= $this->checkSimpleIntField($flexData, $newDSArray, intval($flexData['data']['table_locking']['lDEF']['height']['vDEF']), 'sDEF', 'lDEF', 'vDEF', 'height');
				$modified_flexData |= $this->checkSimpleSelectField($flexData, $newDSArray, $flexData['data']['table_locking']['lDEF']['height_format']['vDEF'], 'sDEF', 'lDEF', 'vDEF', 'height_format');
				$modified_flexData |= $this->checkSimpleSelectField($flexData, $newDSArray, $flexData['data']['table_locking']['lDEF']['align']['vDEF'], 'sDEF', 'lDEF', 'vDEF', 'align');
				$modified_flexData |= $this->checkSimpleTextField($flexData, $newDSArray, $flexData['data']['table_locking']['lDEF']['style']['vDEF'], 'sDEF', 'lDEF', 'vDEF', 'style');
				$modified_flexData |= $this->checkSimpleTextField($flexData, $newDSArray, $flexData['data']['table_locking']['lDEF']['class']['vDEF'], 'sDEF', 'lDEF', 'vDEF', 'class');
				$modified_flexData |= $this->checkSimpleTextField($flexData, $newDSArray, $flexData['data']['table_locking']['lDEF']['id']['vDEF'], 'sDEF', 'lDEF', 'vDEF', 'id');
				$modified_flexData |= $this->checkSimpleTextField($flexData, $newDSArray, $flexData['data']['table_locking']['lDEF']['additional']['vDEF'], 'sDEF', 'lDEF', 'vDEF', 'additional');
				$modified_flexData |= $this->checkRows($flexData, $newDSArray, $flexData['data']['table_locking']['lDEF']['rows']['vDEF']);
				$modified_flexData |= $this->checkFields($flexData, $newDSArray, $flexData['data']['table_locking']['lDEF']['columns']['vDEF']);
			}
			if ($modified_flexData)	{
						// Write back modified flex data field.
					$dataArray = Array();
						// Only active after first save with initialized fields.
					$dataArray['tt_content'][$row['uid']][$this->flexField] = t3lib_div::array2xml_cs($flexData, 'T3DataStructure');
					$tce = t3lib_div::makeInstance('t3lib_TCEmain');
					$tce->start($dataArray, Array());
					$tce->process_datamap();
			}
			$dsArray = $newDSArray;
		}

	}

	/**
	 * Checks all fields of a cell
	 *
	 * @param	array		Flexform data array (passed by reference)
	 * @param	array		Flexform DS array (passed by reference)
	 * @param	string		Defines which columns are locked
	 * @return	boolean		Wheter the flexData was modified
	 */
	function checkFields(&$flexData, &$newDSArray, $val)	{
		$modified_flexData = false;
		$rows = t3lib_div::trimExplode(',', $flexData['data']['sDEF']['lDEF']['rows']['vDEF'], 1);
		$table = array();
		$cols = 0;
		$saveCols = 0;
		foreach ($rows as $row)	{
			$columns = t3lib_div::trimExplode(',', $flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'], 1);
			$cols = count($columns);
			if (!$saveCols)	{
				$saveCols = $cols;
			} else	{
				if ($saveCols != $cols)	{
					return false;
				}
			}
			foreach ($columns as $col)	{
				$table[$row]['columns'] = $columns;
			}
		}
		$conf = Array();
		$conf['hidden_columns'] = $val;
		$hidden_columns = $this->funcs->splitConfArray($conf, $saveCols);
		$conf = Array();
		foreach ($flexData['data']['cell_locking']['lDEF'] as $fieldName => $fieldAr)	{
			$data = $flexData['data']['cell_locking']['lDEF'][$fieldName]['vDEF'];
			$conf[$fieldName] = $flexData['data']['cell_locking']['lDEF'][$fieldName]['vDEF'];
		}
		$splitted = $this->funcs->splitConfArray($conf, count($table));
		$conf = Array();
		foreach ($splitted as $row => $fieldNameAr)	{
			foreach ($fieldNameAr as $fieldName => $rowStr)	{
				$rowStr = str_replace("~*~", '|*|', $rowStr);
				$rowStr = str_replace("~~", '||', $rowStr);
				$conf[$row][$fieldName] = $rowStr;
			}
		}
		$splitted = $this->funcs->splitConfArray($conf, $saveCols);
		foreach ($splitted as $col_idx => $rowAr)	{
			foreach ($rowAr as $row_idx => $propAr)	{
				$row = $rows[$row_idx];
				$col = $table[$row]['columns'][$col_idx];
				if (intval($hidden_columns[$col]['hidden_columns']))	{
					foreach ($newDSArray['sheets']['s_row_'.$row]['ROOT']['el'] as $fieldName => $fieldAr)	{
						$parts = explode('_', $fieldName);
						if (($parts[0]=='column')&&($parts[1]==$row)&&($parts[2]==$col))	{
								// If this column is hidden unset all properties from this column and row.
							unset($newDSArray['sheets']['s_row_'.$row]['ROOT']['el'][$fieldName]);
							$modified_flexData = true;
						}
					}
				} else	{
					foreach($propAr as $fieldName => $value)	{
						switch ($fieldName)	{
							case 'rowspan':
							case 'colspan':
							case 'cellwidth':
							case 'cellheight':
							case 'fontsize':
								$modified_flexData |=$this->checkSimpleIntField($flexData, $newDSArray, $value, 's_row_'.$row, 'lDEF', 'vDEF', 'column_'.$row.'_'.$col.'_'.$fieldName);
							break;
							case 'celltype':
							case 'align':
							case 'valign':
							case 'fontweight':
							case 'cellwidth_format':
							case 'cellheight_format':
							case 'wordwrap':
							case 'fontsize_format':
								$modified_flexData |= $this->checkSimpleSelectField($flexData, $newDSArray, $value, 's_row_'.$row, 'lDEF', 'vDEF', 'column_'.$row.'_'.$col.'_'.$fieldName);
							break;
							case 'fontfamily':
							case 'class':
							case 'id':
							case 'style':
							case 'color':
							case 'backgroundcolor':
							case 'additional':
								$modified_flexData |= $this->checkSimpleTextField($flexData, $newDSArray, $value, 's_row_'.$row, 'lDEF', 'vDEF', 'column_'.$row.'_'.$col.'_'.$fieldName);
							break;
							case 'content':
								if (intval($value)&&$this->restrict)	{
									if (is_array($newDSArray['sheets']['s_row_'.$row]['ROOT']['el']['column_'.$row.'_'.$col.'_elements']))	{
										unset($newDSArray['sheets']['s_row_'.$row]['ROOT']['el']['column_'.$row.'_'.$col.'_elements']);
										$modified_flexData = true;
									}
								}
							break;
							case 'props':
								if (intval($value)&&$this->restrict)	{
									if (isset($newDSArray['sheets']['s_row_'.$row]['ROOT']['el']))	{
										foreach ($newDSArray['sheets']['s_row_'.$row]['ROOT']['el'] as $fieldName => $fieldAr)	{
											if (($parts[0]=='column')&&($parts[1]==$row)&&($parts[2]==$col))	{
													// If this column is hidden unset all properties from this column and row.
													// Except content when props is set;
												if ($parts[3]=='elements') continue;
												if (is_array($newDSArray['sheets']['s_row_'.$row]['ROOT']['el'][$fieldName]))	{
													unset($newDSArray['sheets']['s_row_'.$row]['ROOT']['el'][$fieldName]);
													$modified_flexData = true;
												}
											}
										}
									}
								}
							break;
							default:
								echo 'Unconfigured field "'.$fieldName.'" in Locking Table<br>'.chr(10);
							break;
						} // switch ($fieldName)	{
					} // foreach($propAr as $fieldName => $value)	{
				} // if (intval($hidden_columns[$col]['hidden_columns'])) { ... } else	{
			} // foreach ($rowAr as $row => $propAr)	{
		} // foreach ($splitted as $col => $rowAr)	{
		return $modified_flexData;
	}

	/**
	 * Checks which rows need to get hidden
	 *
	 * @param	array		Flexform data array (passed by reference)
	 * @param	array		Flexform DS array (passed by reference)
	 * @param	string		Defines which rows are locked
	 * @return	boolean		Wheter the flexData was modified
	 */
	function checkRows(&$flexData, &$newDSArray, $val)	{
		$modified_flexData = false;
		$rows = t3lib_div::trimExplode(',', $flexData['data']['sDEF']['lDEF']['rows']['vDEF'], 1);
		$list = $this->funcs->splitconfArray(Array('kbconttable' =>$val), count($rows));
		$cnt = 0;
		if ($this->restrict)	{
			foreach ($rows as $row)	{
				if (intval($list[$cnt]['kbconttable']))	{
					if (isset($newDSArray['sheets']['s_row_'.$row]))	{
						unset($newDSArray['sheets']['s_row_'.$row]);
						$modified_flexData = true;
					}
				}
				$cnt++;
			}
		}
		return $modified_flexData;
	}

	/**
	 * Checks a simple Text field
	 *
	 * @param	array		Flexform data array (passed by reference)
	 * @param	array		Flexform DS array (passed by reference)
	 * @param	string		The locking value
	 * @param	string		Sheet
	 * @param	string		lDEF
	 * @param	string		vDEF
	 * @param	string		field
	 * @return	boolean		Wheter the flexData was modified
	 */
	function checkSimpleTextField(&$flexData, &$newDSArray, $val, $val_sheet, $lDEF, $vDEF, $field)	{
		$modified_flexData = false;
		if (strlen(trim($val)))	{
			/*
			if ($val != $flexData['data'][$val_sheet][$lDEF][$field][$vDEF])	{
				$flexData['data'][$val_sheet][$lDEF][$field][$vDEF] = $val;
				$modified_flexData = true;
			}
			*/
			if (is_array($newDSArray['sheets'][$val_sheet]['ROOT']['el'][$field])&&$this->restrict)	{
				$newDSArray['sheets'][$val_sheet]['ROOT']['el'][$field]['TCEforms']['config'] = Array('type' => 'none');
			}
		}
		return $modified_flexData;
	}

	/**
	 * Checks a simple Select field
	 *
	 * @param	array		Flexform data array (passed by reference)
	 * @param	array		Flexform DS array (passed by reference)
	 * @param	string		The locking value
	 * @param	string		Sheet
	 * @param	string		lDEF
	 * @param	string		vDEF
	 * @param	string		field
	 * @return	boolean		Wheter the flexData was modified
	 */
	function checkSimpleSelectField(&$flexData, &$newDSArray, $val, $val_sheet, $lDEF, $vDEF, $field)	{
		$modified_flexData = false;
		if (!(intval($val)<0))	{
			/*
			if ($val != $flexData['data'][$val_sheet][$lDEF][$field][$vDEF])	{
				$flexData['data'][$val_sheet][$lDEF][$field][$vDEF] = $val;
				$modified_flexData = true;
			}
			*/
			if (is_array($newDSArray['sheets'][$val_sheet]['ROOT']['el'][$field])&&$this->restrict)	{
				$newDSArray['sheets'][$val_sheet]['ROOT']['el'][$field]['TCEforms']['config'] = Array('type' => 'none');
			}
		}
		return $modified_flexData;
	}


	/**
	 * Checks a simple Integer field
	 *
	 * @param	array		Flexform data array (passed by reference)
	 * @param	array		Flexform DS array (passed by reference)
	 * @param	string		The locking value
	 * @param	string		Sheet
	 * @param	string		lDEF
	 * @param	string		vDEF
	 * @param	string		field
	 * @return	boolean		Wheter the flexData was modified
	 */
	function checkSimpleIntField(&$flexData, &$newDSArray, $val, $val_sheet, $lDEF, $vDEF, $field)	{
		$modified_flexData = false;
		if (strlen(trim($val))&&(intval($val) >= 0))	{
			/*
			if (intval($val) != intval($flexData['data'][$val_sheet][$lDEF][$field][$vDEF]))	{
				$flexData['data'][$val_sheet][$lDEF][$field][$vDEF] = intval($val);
				$modified_flexData = true;
			}
			*/
			if (is_array($newDSArray['sheets'][$val_sheet]['ROOT']['el'][$field])&&$this->restrict)	{
				$newDSArray['sheets'][$val_sheet]['ROOT']['el'][$field]['TCEforms']['config'] = Array('type' => 'none');
			}
		}
		return $modified_flexData;
	}

	/**
	 * Checks a simple Checkbox field
	 *
	 * @param	array		Flexform data array (passed by reference)
	 * @param	array		Flexform DS array (passed by reference)
	 * @param	string		The locking value
	 * @param	string		Sheet
	 * @param	string		lDEF
	 * @param	string		vDEF
	 * @param	string		field
	 * @return	boolean		Wheter the flexData was modified
	 */
	function checkSimpleCheckField(&$flexData, &$newDSArray, $val, $val_sheet, $lDEF, $vDEF, $field)	{
		$modified_flexData = false;
		if (strlen(trim($val))&&(intval($val) >= 0))	{
			/*
			if ((intval($val)?1:0) != (intval($flexData['data'][$val_sheet][$lDEF][$field][$vDEF])?1:0))	{
				$flexData['data'][$val_sheet][$lDEF][$field][$vDEF] = intval($val)?1:0;
				$modified_flexData = true;
			}
			*/
			if (is_array($newDSArray['sheets'][$val_sheet]['ROOT']['el'][$field])&&$this->restrict)	{
				$newDSArray['sheets'][$val_sheet]['ROOT']['el'][$field]['TCEforms']['config'] = Array('type' => 'none');
			}
		}
		return $modified_flexData;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_t3lib_befunc.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_t3lib_befunc.php']);
}

?>
