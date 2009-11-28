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
 * kb_conttable module tt_content_tx_kbconttable_flex_dswiz0
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
 *  134: class fullDoc extends template
 *
 *
 *  142: class tx_kbconttable_tt_content_tx_kbconttable_flex_dswiz extends t3lib_SCbase
 *
 *              SECTION: INITIALIZE
 *  164:     function initFlexData($xml)
 *
 *              SECTION: WIZARD MODULE
 *  187:     function menuConfig()
 *  205:     function main()
 *  509:     function printContent()
 *  521:     function moduleContent()
 *
 *              SECTION: TABLE MODIFICATION
 *  571:     function checkCellProps()
 *  637:     function checkResetSpans()
 *  657:     function checkRowColumnCellHide()
 *  708:     function checkRowColumnMove()
 *  839:     function checkRowColumnDelete()
 *  928:     function checkRowColumnCreate()
 *
 *              SECTION: RENDERING
 * 1012:     function getRTEContent($cell, $row, $col)
 * 1031:     function getTable()
 * 1086:     function getTable_Header()
 * 1128:     function getCellEdit()
 * 1343:     function getHiddenArray($ar, $Glabel)
 * 1358:     function getHiddenForm()
 * 1386:     function getJS()
 * 1390:     function goto_returnurl()
 * 1445:     function error($error, $not_stored = 0)
 *
 *              SECTION: CHECKING
 * 1478:     function checkRowColumnCount($validate = 0)
 *
 *              SECTION: ITERATION METHODS
 * 1519:     function iter_getTable_rowBegin(&$params)
 * 1558:     function iter_getTable_column(&$params)
 * 1623:     function iter_getTable_rowEnd(&$params)
 * 1634:     function iter_chkRowColCount_rowEnd(&$params)
 * 1651:     function iter_getHiddenForm_column(&$params)
 *
 *              SECTION: CONTENT ELEMENTS OPERATIONS
 * 1725:     function cmd_createNewRecord ($parentRecord, $defVals='')
 * 1745:     function cmd_unlinkRecord ($unlinkRecord)
 * 1756:     function cmd_deleteRecord ($deleteRecord)
 * 1768:     function cmd_makeLocalRecord ($makeLocalRecord)
 *
 *              SECTION: SUPPORTING METHODS
 * 1791:     function array_swap($array, $start1, $length1, $start2, $length2)
 *
 * TOTAL FUNCTIONS: 31
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



	// DEFAULT initialization of a module [BEGIN]

define('MAX_ROW_ID', 50);		// Probably too high
define('MAX_COL_ID', 50);

unset($MCONF);
require ('conf.php');
require ($BACK_PATH.'init.php');
require ($BACK_PATH.'template.php');
$LANG->includeLLFile('EXT:kb_conttable/tt_content_tx_kbconttable_flex_ds/locallang.php');
require_once (PATH_t3lib.'class.t3lib_scbase.php');
require_once (PATH_t3lib.'class.t3lib_tcemain.php');
require_once(PATH_t3lib.'class.t3lib_recordlist.php');
require_once(PATH_t3lib.'class.t3lib_clipboard.php');
require_once(PATH_t3lib.'class.t3lib_tceforms.php');
require_once(PATH_typo3.'class.db_list.inc');
require_once (t3lib_extMgm::extPath('cms').'layout/class.tx_cms_layout.php');
require_once (t3lib_extMgm::extPath('kb_conttable').'class.ux_t3lib_clipboard.php');
require_once (t3lib_extMgm::extPath('kb_conttable').'class.tx_kbconttable_funcs.php');
require_once (t3lib_extMgm::extPath('kb_conttable').'class.tx_kbconttable_layout.php');
require_once (t3lib_extMgm::extPath('kb_conttable').'class.tx_kbconttable_berenderCE.php');
if (t3lib_extMgm::isLoaded('templavoila'))	{
	require_once(t3lib_extMgm::extPath('templavoila').'class.tx_templavoila_xmlrelhndl.php');
} else	{
	require_once (t3lib_extMgm::extPath('kb_conttable').'class.tx_templavoila_xmlrelhndl.php');
}
require_once (t3lib_extMgm::extPath('kb_conttable').'class.tx_kbconttable_xmlrelhndl.php');

if (t3lib_extMgm::isLoaded('rte'))	{
	require_once(t3lib_extMgm::extPath('rte').'class.tx_rte_base.php');
}
if (t3lib_extMgm::isLoaded('rtehtmlarea'))	{
	require_once(t3lib_extMgm::extPath('rtehtmlarea').'class.tx_rtehtmlarea_base.php');
}

	/**
	 * [Describe function...]
	 *
	 */
class fullDoc extends template	{
	var $divClass = 'typo3-fullDoc';
}

	/**
	 * [Describe function...]
	 *
	 */
class tx_kbconttable_tt_content_tx_kbconttable_flex_dswiz extends t3lib_SCbase	{
//	var $flexField = 'tx_templavoila_flex';
	var $flexField = 'tx_kbconttable_flex';

	var $altRoot = Array(
		'table' => 'tt_content',
//		'field_flex' => 'tx_templavoila_flex',
		'field_flex' => 'tx_kbconttable_flex',
	);

	var $tableSettings = Array();
	/****************************************
	 *
	 * INITIALIZE
	 *
	 * This functions load XML code or parse it using T3 methods.
	 *
	 ****************************************/


	/**
	 * Initialize a Flexform XML field
	 *
	 * @param	string		XML String
	 * @return	array		XML Array
	 */
	function initFlexData($xml)	{
		if (strlen($xml))	{
			$res = t3lib_div::xml2array($xml);
		} else	{
			return $res = Array();
		}
		return $res;
	}

	/****************************************
	 *
	 * WIZARD MODULE
	 *
	 * This are the default functions for each wizard
	 *
	 ****************************************/


	/**
	 * Configure the function menu
	 *
	 * @return	void
	 */
	function menuConfig()	{
		global $LANG;
		$this->MOD_MENU = Array (
			'function' => Array (
				'1' => $LANG->getLL('function_edittable_medium'),
				'2' => $LANG->getLL('function_edittable_big'),
				'3' => $LANG->getLL('function_edittable_no'),
			),
			'clipBoard' => '',
		);
		parent::menuConfig();
	}

	/**
	 * Main function of the module. Write the content to $this->content
	 *
	 * @return	void
	 */
	function main()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

		$this->LANG = &$LANG;

		$this->showClipboard = 1;

		$this->colPos = isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['colPos']) ? intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['colPos']) : 10;
		$this->dbMode = intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['dbMode']) ? true : false;
		$this->fastMode = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['fastMode'] ? true : false;

			// Draw the header.
		switch((string)$this->MOD_SETTINGS['function'])	{
			case 1:
				$this->doc = t3lib_div::makeInstance('mediumDoc');
			break;
			case 2:
				$this->doc = t3lib_div::makeInstance('bigDoc');
			break;
			case 3:
				$this->doc = t3lib_div::makeInstance('fullDoc');
			break;
		}
		$this->doc->backPath = $BACK_PATH;
		$this->doc->form='<form name="editform" action="index.php" method="POST" enctype="multipart/form-data">';

  		$this->P = t3lib_div::GPvar('P', 1);
		$this->pid = $this->id = $this->P['pid'];
		$this->uid = $this->P['uid'];
  		$this->GET = t3lib_div::GPvar('kbconttable', 1);

		$this->clipObj = t3lib_div::makeInstance('t3lib_clipboard');
		$this->clipObj->backPath = $this->doc->backPath;
		$this->clipObj->initializeClipboard();

		$available = 0;
		$this->rteMode = 'none';
		if (!$available && t3lib_extMgm::isLoaded('rte'))	{
			$this->rteObj = t3lib_div::makeInstance('tx_rte_base');
			$available = $this->rteObj->isAvailable();
			if ($available)	{
				$this->rteMode = 'default';
			}
		}
		if (!$available && t3lib_extMgm::isLoaded('rtehtmlarea'))	{
			$this->rteObj = t3lib_div::makeInstance('tx_rtehtmlarea_base');
			$available = $this->rteObj->isAvailable();
			if ($available)	{
				$this->rteMode = 'rtehtmlarea';
			}
		}
		if ($this->rteMode != 'none')	{
			$this->tceforms = t3lib_div::makeInstance('t3lib_TCEforms');
			$this->tceforms->initDefaultBEmode();
			$this->tceforms->backPath = $this->doc->backPath;
		}
			// Clipboard actions are handled:
		$CB = t3lib_div::GPvar('CB');	// CB is the clipboard command array
		if ($this->cmd=='setCB')	{
				// CBH is all the fields selected for the clipboard, CBC is the checkbox fields which were checked. By merging we get a full array of checked/unchecked elements
				// This is set to the 'el' array of the CB after being parsed so only the table in question is registered.
			$CB['el'] = $this->clipObj->cleanUpCBC(array_merge(t3lib_div::_POST('CBH'),t3lib_div::_POST('CBC')),$this->cmd_table);
		}
		if (!$this->MOD_SETTINGS['clipBoard'])	$CB['setP']='normal';	// If the clipboard is NOT shown, set the pad to 'normal'.
		$this->clipObj->setCmd($CB);		// Execute commands.
		$this->clipObj->cleanCurrent();	// Clean up pad
		$this->clipObj->endClipboard();	// Save the clipboard content

		$this->funcs = t3lib_div::makeInstance('tx_kbconttable_funcs');
		$this->funcs->init($this);

		$this->xmlhandler = t3lib_div::makeInstance('tx_kbconttable_xmlrelhndl');
		$this->xmlhandler->init($this->altRoot);

		// Get Icons
		$this->Pic_moveLeft = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/button_left.gif','width="11" height="10"');
		$this->Pic_moveRight = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/button_right.gif','width="11" height="10"');
		$this->Pic_moveUp = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/button_up.gif','width="11" height="10"');
		$this->Pic_moveDown = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/button_down.gif','width="11" height="10"');
		$this->Pic_edit = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/edit2.gif','width="11" height="12"');
		$this->Pic_save = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/savedok.gif','width="21" height="16"');
		$this->Pic_saveAndClose = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/saveandclosedok.gif','width="21" height="16"');
		$this->Pic_close = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/closedok.gif','width="21" height="16"');
		$this->Pic_create = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/new_el.gif','width="11" height="12"');
		$this->Pic_delete = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/garbage.gif','width="11" height="12"');
		$this->Pic_hide = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/button_hide.gif','width="11" height="10"');
		$this->Pic_unhide = t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/button_unhide.gif','width="11" height="10"');

			// JavaScript
		$this->doc->JScode .= '<script language="JavaScript" type="text/javascript" src="'.$this->doc->backPath.'../t3lib/jsfunc.updateform.js"></script>';
		$this->doc->JScode .= $this->getJS();
		$this->doc->inDocStyles = '
DIV.typo3-fullDoc { margin: 2px 10px 2px 10px; }
TABLE TH.typo3-kbconttable-lefttop { background-color: #e3efdb; }
TABLE TH.typo3-kbconttable-header { height: 50px; }
TABLE TH.typo3-kbconttable-header-row { height: 100%; }
TABLE TH.typo3-kbconttable-header TABLE { width: 100%; height: 100%; }
TABLE TH.typo3-kbconttable-header TABLE TD.bgColor2 { height: 50%; text-align: center; font-weight: bold; }
TABLE TH.typo3-kbconttable-header-row TABLE TD.bgColor2 { height: 33%; text-align: center; font-weight: bold; }
.typo3-kbconttable-cellheader { padding: 2px; background-color: #9BA1A8; }
.typo3-kbconttable-cellheader-hidden { padding: 2px; background-color: #7E8389; }
.typo3-kbconttable-celabel { padding: 0px 5px 0px 20px; font-weight: bold; }
.typo3-kbconttable-ceinput input { border: 1px solid #000000; }
.typo3-kbconttable-rte { border: 1px dotted #000000; padding: 0px; margin: 2px; }
.bgColor2-hidden { background-color: #7E8389; }
.bgColor5-hidden { background-color: #84918B; }
		';

		// Setting up support for context menus (when clicking the items icon)
		$CMparts = $this->doc->getContextMenuCode();
		$this->doc->bodyTagAdditions = $CMparts[1];
		$this->doc->JScode.= $CMparts[0];
		$this->doc->postCode.= $CMparts[2];

		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;
		$this->admin = $BE_USER->user['admin']?1:0;
		if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id))	{
			if ($BE_USER->user['admin'] && !$this->id)	{
				$this->pageinfo=array('title' => '[root-level]','uid'=>0,'pid'=>0);
			}
			$commands = array ('createNewRecord', 'unlinkRecord', 'deleteRecord', 'makeLocalRecord' /*,'pasteRecord', 'createNewTranslation' */);
			foreach ($commands as $cmd)	{
				unset ($params);
				$params = $this->GET['funcs'][$cmd];
				$function = 'cmd_'.$cmd;

					// If the current function has a parameter passed by GET or POST, call the related function:
				if ($params && is_callable(array ($this, $function)))	{
				 	$this->$function($params);
				}
			}


			if ($this->dbMode) {
				$this->flexData = $this->initTableData($this->currentRow['uid']);
			} else {
				// Get table style from Flexform field.
				$this->currentRow = t3lib_BEfunc::getRecord($this->P['table'], $this->uid);
				if (!strlen($this->flexDS = $this->currentRow[$this->P['field']]))	{
					$this->flexDS = $this->funcs->defaultFlexDS();
					$update = Array(
						$this->P['field'] => $this->flexDS,
					);
					$GLOBALS['TYPO3_DB']->exec_UPDATEquery($this->P['table'], 'uid='.$this->uid, $update);
				}
				$this->flexDS = $this->initFlexData($this->flexDS);

				$this->flexData = $this->initFlexData($this->currentRow[$this->flexField]);
			}

			$this->tableSettings = $this->funcs->getTableSettings($this->flexData);

			// Prepare table data ---- begin
			$this->tableData = $this->funcs->getTableData($this->tableSettings, $this->flexData);

			if (!count($this->tableData))	{
				$this->flexDS = $this->funcs->getDefaultTable_DataDS($this->flexDS, $this->flexData);
				$this->flexData = $this->funcs->setDataFields_byDS($this->flexDS, $this->flexData);
				$this->flexDSXML = t3lib_div::array2xml($this->flexDS, '', 0, 'T3DataStructure');
				$this->flexDataXML = t3lib_div::array2xml($this->flexData, '', 0, 'T3FlexForms');
				$update = Array(
					$this->P['field'] => $this->flexDSXML,
					$this->flexField => $this->flexDataXML,
				);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery($this->P['table'], 'uid='.$this->uid, $update);
				$this->tableSettings = $this->funcs->getTableSettings($this->flexData);
				$this->tableData = $this->funcs->getTableData($this->tableSettings, $this->flexData);
			}
			// Prepare table data ---- end

			$content = '';

			// Check row and column count ---- begin
			list($cont, $err) = $this->checkRowColumnCount();
			$content .= $cont;
			// Check row and column count ---- end


			if (!$err)	{
				$this->modified_flexData = false;
				$this->flexData_orig = $this->flexData;
				$this->flexDS_orig = $this->flexDS;
				$this->checkCellProps();
				$this->checkResetSpans();
				$this->checkRowColumnCellHide();
				$this->checkRowColumnMove();
				$this->checkRowColumnDelete();
				$this->checkRowColumnCreate();
				if ($this->modified_flexData||$this->modified_flexDS)	{
					$this->flexData = $this->funcs->setDataFields_byDS($this->flexDS, $this->flexData);
					$this->flexData = $this->filter_unneededFlexData($this->flexData);
					$this->flexDS = $this->filter_unneededFlexDS($this->flexDS, $this->flexData);
					// Retrieve Table settings again
					$this->tableSettings = $this->funcs->getTableSettings($this->flexData);
					// Retrieve Table Data again
					$this->tableData = $this->funcs->getTableData($this->tableSettings, $this->flexData);
					// Check row and column count, save or restore data ---- begin
					list($cont, $err) = $this->checkRowColumnCount(1);
					$content .= $cont;
					if ($err)	{
						$this->flexData = $this->flexData_orig;
						$this->flexDS = $this->flexDS_orig;
						$this->flexData = $this->funcs->setDataFields_byDS($this->flexDS, $this->flexData);
						$this->tableSettings = $this->funcs->getTableSettings($this->flexData);
						$this->tableData = $this->funcs->getTableData($this->tableSettings, $this->flexData);
						list($cont, $err) = $this->checkRowColumnCount();
						$content .= $cont;
					} else	{
						$this->flexDSXML = t3lib_div::array2xml($this->flexDS, '', 0, 'T3DataStructure');
						$this->flexDataXML = t3lib_div::array2xml($this->flexData, '', 0, 'T3FlexForms');
						$update = Array(
							$this->P['field'] => $this->flexDSXML,
							$this->flexField => $this->flexDataXML,
						);
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery($this->P['table'], 'uid='.$this->uid, $update);
						if (isset($this->location))	{
							header('Location: '.t3lib_div::locationHeaderUrl($this->location));
						}
					}
					// Check row and column count, save or restore dat ---- end
				}
			}

			if ($this->GET['close'])	{
				header('Location: '.t3lib_div::locationHeaderUrl($this->P['returnUrl']));
			}
			$error_content = $content;

			$this->rteMode = intval($this->flexData['data']['sDEF']['lDEF']['rte_mode']['vDEF'])?$this->rteMode:'';

			$this->content = '';
			// Render content ---- begin
			$this->moduleContent();
			// Render content ---- end
			$module_content = $this->content;

			$this->content = '';
				// Start Page
			$this->doc->JScode .= '<script type="text/javascript" src="'.$this->doc->backPath.'md5.js"></script>
						<script type="text/javascript" src="'.$this->doc->backPath.'t3lib/jsfunc.evalfield.js"></script>'.chr(10);
//			$this->doc->JScode .= $this->tceforms->printNeededJSFunctions_top();
			if (is_array($this->tceforms->additionalCode_pre))	{
				$this->doc->JScode .= '<!-- // additionalCode_pre begin -->';
				$this->doc->JScode .= implode(chr(10), $this->tceforms->additionalCode_pre);
				$this->doc->JScode .= '<!-- // additionalCode_pre end -->';
			}
			if (is_array($this->tceforms->additionalJS_pre))	{
				$this->doc->JScode .= '<!-- // additionalJS_pre begin -->';
				$this->doc->JScode .= '<script language="JavaScript" type="text/javascript">
					'.implode(chr(10), $this->tceforms->additionalJS_pre).'
					</script>'.chr(10);
				$this->doc->JScode .= '<!-- // additionalJS_pre end -->';
			}
			$this->content.=$this->doc->startPage($LANG->getLL('title'));
			$headerSection = $this->doc->getHeader('pages',$this->pageinfo,$this->pageinfo['_thePath']).'<br>'.$LANG->sL('LLL:EXT:lang/locallang_core.php:labels.path').': '.t3lib_div::fixed_lgd_pre($this->pageinfo['_thePath'],50);

			if (strlen($errror_content))	{
				$this->content.=$this->doc->section('Main', $error_content, 0, 1);
			}

			$this->content.=$this->doc->header($LANG->getLL('title'));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->section('',$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu(array('id' => $this->id, 'P' => $this->P),'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function'])));
			$this->content.=$this->doc->divider(5);

			$this->content .= $module_content;

			$this->content .= '<script language="JavaScript" type="text/javascript">
			/*<![CDATA[*/'.chr(10);
			$this->content .= 'var rteMode = "'.$this->rteMode.'";'.chr(10);
			switch ($this->rteMode)	{
				case 'rtehtmlarea':
					$this->content .= 'function RTEshow() {
	RTEarea[0]["editor"].setMode("wysiwyg");
}



'.chr(10);
					$this->content .= 'function RTEinit() {';
					foreach ($this->tceforms->additionalJS_post as $js)	{
						$js = str_replace('/*<![CDATA[*/', '', $js);
						$js = str_replace('/*]]>*/', '', $js);
						$this->content .= $js;
					}
					$this->content .= '}'.chr(10);
				break;
				case 'default':
				break;
				case 'none':
				break;
				default:
				break;
			}
			$this->content .='/*]]>*/
			</script>'.chr(10);

			// ShortCut ---- begin
			if ($BE_USER->mayMakeShortcut())	{
				$this->content.=$this->doc->spacer(20).$this->doc->section('',$this->doc->makeShortcutIcon('id',implode(',',array_keys($this->MOD_MENU)),$this->MCONF['name']));
			}
			// ShortCut ---- end
		}
		$this->content.=$this->doc->spacer(10);
	}

	/**
	 * print out content
	 *
	 * @return	void
	 */
	function printContent()	{
		$this->content .= $this->doc->endPage();
		echo $this->content;
	}



	/**
	 * Generate module content
	 *
	 * @return	string		HTML
	 */
	function moduleContent()	{
		switch((string)$this->MOD_SETTINGS['function'])	{
			case 3:
			case 2:
			case 1:
				$err = 0;
				// Render Table ---- begin
				if (!$err)	{
					list($err, $hidden) = $this->getHiddenForm();
					if ($err < 0)	{
						$content .= $hidden;
					} else	{
						$content .= $hidden;
						list($table_err, $table) = $this->getTable();
						$content .= $table;
					}
				}
				// Render Table ---- end
				$this->content.=$this->doc->section('Table', $content, 0, 1);
				if ($table_err >= 0)	{
					$this->content.=$this->doc->spacer(15);
					$this->content.= '<div id="DTM-cellprops-DIV" class="c-tablayer" style="display: none;">'.chr(10);
					$this->content.= '<a name="cellprops"></a>'.chr(10);
					$this->content.=$this->doc->section('Cell Properties', $this->getCellEdit(), 0, 1);
					$this->content.= '</div>';

					if ($this->MOD_SETTINGS['clipBoard'] && $this->showClipboard)	{
						$this->content .= $this->clipObj->printClipboard();
					}

				}
			break;
		}
	}



	/****************************************
	 *
	 * TABLE MODIFICATION
	 *
	 * This functions perform operations on the table (Move, Insert, Delete)
	 *
	 ****************************************/

	/**
	 * Checks if a cell properties were modified
	 *
	 * @return	void
	 */
	function checkCellProps()	{
		$rows = t3lib_div::trimExplode(',', $this->tableSettings['rows'], 1);
		if (is_array($this->GET['data']))	{
			foreach ($this->GET['data'] as $row_idx => $row_ar)	{
				$row_idx = intval($row_idx);
				if (($row_idx < 1)||($row_idx > $this->rows)) continue;
				$row = $rows[$row_idx-1];
				$columns = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'], 1);
				foreach ($row_ar as $col_idx => $column_ar)	{
					$col_idx = intval($col_idx);
					if (($col_idx < 1)||($col_idx > $this->columns)) continue;
					$col = $columns[$col_idx-1];
					foreach ($column_ar as $key => $value)	{
						if (strpos($key, 'lock_')===0) continue;
						switch ($key)	{
							case 'colspan':
							case 'rowspan':
								if ($this->tableData[$row][$col]['lock_'.$key]!=0) continue;
							break;
							case 'rte_content':
								if ($this->tableData[$row][$col]['lock_content']!=0) continue;
							break;
							case 'cellwidth':
							case 'cellheight':
							case 'fontsize':
								if ($this->tableData[$row][$col]['lock_'.$key]!=-1) continue;
							case 'celltype':
							case 'wordwrap':
							case 'cellwidth_format':
							case 'cellheight_format':
							case 'align':
							case 'valign':
							case 'fontsize_format':
							case 'fontweight':
								// The same for drop-down boxes and strings
							case 'backgroundcolor':
							case 'color':
							case 'class':
							case 'id':
							case 'fontfamily':
							case 'style':
							case 'additional':
								if ($this->tableData[$row][$col]['lock_'.$key]!=-1) continue;
							break;
							default:
								echo 'Invalid key "'.$key.'" POSTed !<br>'.chr(10);
								continue;
							break;
						}
						if ($key=='rte_content')	{
							$this->flexData['data']['s_row_'.$row]['lDEF']['column_'.$row.'_'.$col.'_'.$key]['vDEF'] = rawurldecode($value);
						} else	{
							if ($this->fastMode)	{
								$this->tableData[$row][$col]['fastprops'][$key] = $value;
							} else	{
								$this->flexData['data']['s_row_'.$row]['lDEF']['column_'.$row.'_'.$col.'_'.$key]['vDEF'] = $value;
							}
						}
					}
					if ($this->fastMode)	{
						$this->flexData['data']['s_row_'.$row]['lDEF']['column_'.$row.'_'.$col.'_fastprops']['vDEF'] = serialize($this->tableData[$row][$col]['fastprops']);
					}
				}
			}
			$this->modified_flexData = true;
		}
	}

	/**
	 * Checks if a row and colspans should get reseted
	 *
	 * @return	void
	 */
	function checkResetSpans()	{
		if (intval($this->GET['reset_colrowspan']))	{
			$rows = t3lib_div::trimExplode(',', $this->tableSettings['rows'], 1);
			foreach ($rows as $row)	{
				$columns = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'], 1);
				foreach ($columns as $col)	{
					$this->flexData['data']['s_row_'.$row]['lDEF']['column_'.$row.'_'.$col.'_rowspan']['vDEF'] = '1';
					$this->flexData['data']['s_row_'.$row]['lDEF']['column_'.$row.'_'.$col.'_colspan']['vDEF'] = '1';
				}
			}
			$this->modified_flexData = 1;
		}
	}


	/**
	 * Checks if a row, column or cell should get hided
	 *
	 * @return	void
	 */
	function checkRowColumnCellHide()	{
		$rows = t3lib_div::trimExplode(',', $this->tableSettings['rows'], 1);
		if (($hide = intval($this->GET['hide_row']))&&($hide > 0)&&($hide <= $this->rows))	{
			$visible_rows = t3lib_div::trimExplode(',', $this->tableSettings['visible_rows'], 1);
			$row = $rows[$hide-1];
			if ($this->admin||!$this->tableData[$row]['lockaction_hide'])	{
				if (in_array($row, $visible_rows))	{
					$visible_rows = array_diff($visible_rows, array($row));
				} else	{
					$visible_rows[] = $row;
				}
				$this->tableSettings['visible_rows'] = implode(',', $visible_rows);
				$this->flexData['data']['sDEF']['lDEF']['visible_rows']['vDEF'] = $this->tableSettings['visible_rows'];
				$this->modified_flexData = true;
			}
		}
		if (($hide = intval($this->GET['hide_column']))&&($hide > 0)&&($hide <= $this->columns))	{
			if ($this->admin||!$this->tableData['columns'][$hide-1]['lockaction_hide'])	{
				$hidden_cols = t3lib_div::trimExplode(',', $this->tableSettings['hidden_columns'], 1);
				if (in_array($hide, $hidden_cols))	{
					$hidden_cols = array_diff($hidden_cols, array($hide));
				} else	{
					$hidden_cols[] = $hide;
				}
				$this->tableSettings['hidden_columns'] = implode(',', $hidden_cols);
				$this->flexData['data']['sDEF']['lDEF']['hidden_columns']['vDEF'] = $this->tableSettings['hidden_columns'];
				$this->modified_flexData = true;
			}
		}
		list($row, $col) = t3lib_div::trimExplode(',', $this->GET['hide_cell'], 1);
		if (($row = intval($row))&&($col = intval($col))&&($row >= 1)&&($row <= $this->rows)&&($col >= 1)&&($col <= $this->columns))	{
			// Todo: Check for cell-hide-locking
			$row_id = $rows[$row-1];
			$columns = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$row_id]['lDEF']['columns']['vDEF'], 1);
			$col_id = $columns[$col-1];
			$vis_cols = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$row_id]['lDEF']['visible_columns']['vDEF'], 1);
			if (in_array($col_id, $vis_cols))	{
				$vis_cols = array_diff($vis_cols, Array($col_id));
			} else	{
				$vis_cols[] = $col_id;
			}
			$this->flexData['data']['s_row_'.$row_id]['lDEF']['visible_columns']['vDEF'] = implode(',', $vis_cols);
			$this->modified_flexData = true;
		}
	}

	/**
	 * Checks if a row, column or cell should get moved
	 *
	 * @return	void
	 */
	function checkRowColumnMove()	{
		$rows = t3lib_div::trimExplode(',', $this->tableSettings['rows'], 1);
			// Move rows
		if (($up = intval($this->GET['move_row_up']))&&($up > 1)&&($up <= $this->rows))	{
			$row = $rows[$up-1];
			if ($this->admin||!$this->tableData[$row]['lockaction_move_up'])	{
				$rows = $this->array_swap($rows, $up-2, 1, $up-1, 1);
				$this->tableSettings['rows'] = implode(',', $rows);
				$this->flexData['data']['sDEF']['lDEF']['rows']['vDEF'] = $this->tableSettings['rows'];
				$this->modified_flexData = true;
			}
		}
		if (($down = intval($this->GET['move_row_down']))&&($down >= 1)&&($down < $this->rows))	{
			$row = $rows[$down-1];
			if ($this->admin||!$this->tableData[$row]['lockaction_move_down'])	{
				$rows = $this->array_swap($rows, $down-1, 1, $down, 1);
				$this->tableSettings['rows'] = implode(',', $rows);
				$this->flexData['data']['sDEF']['lDEF']['rows']['vDEF'] = $this->tableSettings['rows'];
				$this->modified_flexData = true;
			}
		}
			// Move columns
		$move_columns = 0;
		if (($left = intval($this->GET['move_column_left']))&&($left > 1)&&($left <= $this->columns))	{
			if ($this->admin||!$this->tableData['columns'][$left-1]['lockaction_move_left'])	{
				$params = Array(
					'first' => $left-1,
					'second' => $left,
				);
				$move_columns = 1;
			}
		}
		if (($right = intval($this->GET['move_column_right']))&&($right >= 1)&&($right < $this->columns))	{
			if ($this->admin||!$this->tableData['columns'][$right-1]['lockaction_move_right'])	{
				$params = Array(
					'first' => $right,
					'second' => $right+1,
				);
				$move_columns = 1;
			}
		}
		if ($move_columns)	{
			foreach ($rows as $row)	{
				$columns = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF']);
				$columns = $this->array_swap($columns, $params['first']-1, 1, $params['second']-1, 1);
				$this->flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'] = implode(',',$columns);
			}
			$this->modified_flexData = true;
		}
		// Move cells horizontally
		$move_cell_hori = 0;
		list($row, $col) = explode(',', $this->GET['move_cell_left']);
		if (($left = intval($col))&&($row = intval($row))&&($left > 1)&&($left <= $this->columns)&&($row >= 1)&&($row <= $this->rows))	{
			$params = Array(
				'first' => $left-1,
				'second' => $left,
				'row' => $row,
			);
			$move_cell_hori = 1;
		}
		list($row, $col) = explode(',', $this->GET['move_cell_right']);
		if (($right = intval($col))&&($row = intval($row))&&($right >= 1)&&($right < $this->columns)&&($row >= 1)&&($row <= $this->rows))	{
			$params = Array(
				'first' => $right,
				'second' => $right+1,
				'row' => $row,
			);
			$move_cell_hori = 1;
		}
		if ($move_cell_hori)	{
			$cnt == 0;
			foreach ($rows as $row)	{
				$cnt++;
				if ($cnt ==$params['row'])	{
					$columns = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF']);
					$columns = $this->array_swap($columns, $params['first']-1, 1, $params['second']-1, 1);
					$this->flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'] = implode(',',$columns);
				}
			}
			$this->modified_flexData = true;
		}
		// Move cells verticall (Very complicated --- would need an improvement)
		$move_cell_vert = 0;
		list($row, $col) = explode(',', $this->GET['move_cell_up']);
		if (($up = intval($row))&&($col = intval($col))&&($up > 1)&&($up <= $this->rows)&&($col >= 1)&&($col <= $this->columns))	{
			$params = Array(
				'first' => $up-1,
				'second' => $up,
				'column' => $col,
			);
			$move_cell_vert = 1;
		}
		list($row, $col) = explode(',', $this->GET['move_cell_down']);
		if (($down = intval($row))&&($col = intval($col))&&($down >= 1)&&($down < $this->rows)&&($col >= 1)&&($col <= $this->columns))	{
			$params = Array(
				'first' => $down,
				'second' => $down+1,
				'column' => $col,
			);
			$move_cell_vert = 1;
		}
		if ($move_cell_vert)	{
			$oldRow = false;
			$cnt = 0;
			foreach ($rows as $row)	{
				$cnt++;
				if ($cnt==$params['second'])	{
					$columns_old = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$oldRow]['lDEF']['columns']['vDEF'], 1);
					$columns = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'], 1);
					$col = $columns[$params['column']-1];
					$col_old = $columns_old[$params['column']-1];
					foreach ($this->flexData['data']['s_row_'.$row]['lDEF'] as $key => $ar)	{
						$parts = explode('_', $key, 4);
						if (($parts[0]=='column')&&($parts[1]==$row)&&($parts[2]==$col))	{
							$tmp = $this->flexData['data']['s_row_'.$oldRow]['lDEF']['column_'.$oldRow.'_'.$col_old.'_'.$parts[3]];
							$this->flexData['data']['s_row_'.$oldRow]['lDEF']['column_'.$oldRow.'_'.$col_old.'_'.$parts[3]] = $this->flexData['data']['s_row_'.$row]['lDEF']['column_'.$row.'_'.$col.'_'.$parts[3]];
							$this->flexData['data']['s_row_'.$row]['lDEF']['column_'.$row.'_'.$col.'_'.$parts[3]] = $tmp;
						}
					}
				}
				$oldRow = $row;
			}
			$this->modified_flexData = true;
		}
	}

	/**
	 * Checks if a row or column should get deleted
	 *
	 * @return	void
	 */
	function checkRowColumnDelete()	{
		$rows = t3lib_div::trimExplode(',', $this->tableSettings['rows'], 1);
		// Delete rows
		if (($delete = intval($this->GET['delete_row']))&&($delete >= 1)&&($delete <= $this->rows))	{
			$row = $rows[$delete-1];
			if ($this->admin||!$this->tableData[$row]['lockaction_delete'])	{
				list($del_row) = array_splice($rows, $delete-1, 1);
				$vis_rows = t3lib_div::trimExplode(',', $this->tableSettings['visible_rows'], 1);
				$vis_rows = array_diff($vis_rows, Array($del_row));
				$this->tableSettings['rows'] = implode(',', $rows);
				$this->tableSettings['visible_rows'] = implode(',', $vis_rows);
				$this->flexData['data']['sDEF']['lDEF']['rows']['vDEF'] = $this->tableSettings['rows'];
				$this->flexData['data']['sDEF']['lDEF']['visible_rows']['vDEF'] = $this->tableSettings['visible_rows'];
				$columns = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$del_row]['lDEF']['columns']['vDEF'], 1);
				$cmd = Array();
				/*
					// This is needed when we have a reference count and can safely delete records if the aren't needed any more
				foreach ($columns as $column)	{
					$elements = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$del_row]['lDEF']['column_'.$del_row.'_'.$column.'_elements']['vDEF'], 1);
					foreach ($elements as $element)	{
						$el_ar = t3lib_BEfunc::getRecord('tt_content', $element);
						if ((intval($el_ar['colpos'])==$this->colPos)&&($el_ar['pid']==$this->pid))	{
							// Just delete content elements which are on the same page as the table and
						// which have a defined colpos value (probably a hidden column)
							$cmd['tt_content'][$element]['delete'] = 1;
						}
					}
				}
				$tce = t3lib_div::makeInstance('t3lib_TCEmain');
				$tce->start(Array(), $cmd);
				$tce->process_cmdmap();
				*/
				unset($this->flexDS['sheets']['s_row_'.$del_row]);
				$this->modified_flexDS = true;
				unset($this->flexData['data']['s_row_'.$del_row]);
				$this->modified_flexData = true;
			}
		}
		if (($delete = intval($this->GET['delete_column']))&&($delete >= 1)&&($delete <= $this->columns))	{
			if ($this->admin||!$this->tableData['columns'][$delete-1]['lockaction_delete'])	{
				$hiddenColumns = t3lib_div::trimExplode(',', $this->tableSettings['hidden_columns'], 1);
				$hiddenColumns = array_diff($hiddenColumns, Array($delete));
				$this->flexData['data']['sDEF']['lDEF']['hidden_columns']['vDEF'] = implode(',', $hiddenColumns);
				$cmd = Array();
				foreach ($rows as $row)	{
					$columns = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'], 1);
					list($del_col) = array_splice($columns, $delete-1, 1);
					$this->flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'] = implode(',', $columns);
					$vis_cols = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$row]['lDEF']['visible_columns']['vDEF'], 1);
					$vis_cols = array_diff($vis_cols, Array($del_col));
					$this->flexData['data']['s_row_'.$row]['lDEF']['visible_columns']['vDEF'] = implode(',', $vis_cols);
					$elements = t3lib_div::trimExplode(',', $this->flexData['data']['s_row_'.$row]['lDEF']['column_'.$row.'_'.$del_col.'_elements']['vDEF'], 1);
					/*
						// This is needed when we have a reference count and can safely delete records if the aren't needed any more
					foreach ($elements as $element)	{
						$el_ar = t3lib_BEfunc::getRecord('tt_content', $element);
						if ((intval($el_ar['colpos'])==$this->colPos)&&($el_ar['pid']==$this->pid))	{
							// Just delete content elements which are on the same page as the table and
							// which have a defined colpos value (probably a hidden column)
							$cmd['tt_content'][$element]['delete'] = 1;
						}
					}
					*/
					$flex = $this->flexData['data']['s_row_'.$row]['lDEF'];
					foreach ($flex as $key => $ar)	{
						$parts = explode('_', $key, 4);
						if (($parts[0]=='column')&&($parts[1]==$row)&&($parts[2]==$del_col))	{
							unset($this->flexData['data']['s_row_'.$row]['lDEF'][$key]);
							unset($this->flexDS['sheets']['s_row_'.$row]['ROOT']['el'][$key]);
						}
					}
				}
				/*
					// This is needed when we have a reference count and can safely delete records if the aren't needed any more
				$tce = t3lib_div::makeInstance('t3lib_TCEmain');
				$tce->start(Array(), $cmd);
				$tce->process_cmdmap();
				*/
				$this->modified_flexDS = true;
				$this->modified_flexData = true;
			}
		}
	}

	/**
	 * Checks if a row or column should get created
	 *
	 * @return	void
	 */
	function checkRowColumnCreate()	{
		$rows = t3lib_div::trimExplode(',', $this->tableSettings['rows'], 1);
			// Create rows
		if ((isset($this->GET['create_row']))&&($create >= 0)&&($create <= $this->rows))	{
			$row_before = ($create>0)?$rows[$create-1]:0;
			$row_after = ($create<$this->rows)?$rows[$create]:0;
			if ($this->admin||(($row_before||!$this->tableData[$row_before]['lockaction_insert_after'])&&(!$row_after||!$this->tableData[$row_after]['lockaction_insert_before'])))	{
	//			if (!$this->tableData[$delete-1]['lockaction_delete'])	{
					$create = intval($this->GET['create_row']);
				$new_row = 0;
				for ($x = 1; $x < MAX_ROW_ID; $x++)	{
					if (!in_array($x, $rows))	{
						$new_row = $x;
						break;
					}
				}
				if ($new_row)	{
					array_splice($rows, $create, 0, $new_row);
					$this->tableSettings['rows'] = implode(',', $rows);
					$this->flexData['data']['sDEF']['lDEF']['rows']['vDEF'] = $this->tableSettings['rows'];
					$this->flexData['data']['sDEF']['lDEF']['visible_rows']['vDEF'] .= (strlen($this->flexData['data']['sDEF']['lDEF']['visible_rows']['vDEF'])?',':'').$new_row;
					$sheet = 's_row_'.$new_row;
					list($xmlPart, $columnsStr) = $this->funcs->getDefaultTable_RowDS($new_row, $this->columns);
					$this->flexDS['sheets'][$sheet] = $xmlPart;
					if (!is_array($this->flexData['data'][$sheet]))	{
						$this->flexData['data'][$sheet] = Array();
					}
					$this->flexData['data'][$sheet]['lDEF']['columns']['vDEF'] = $columnsStr;
					$this->flexData['data'][$sheet]['lDEF']['visible_columns']['vDEF'] = $columnsStr;
					$this->modified_flexData = true;
					$this->modified_flexDS = true;
				}
			}
		}
		if ((isset($this->GET['create_column']))&&($create >= 0)&&($create <= $this->columns))	{
			$create = intval($this->GET['create_column']);
			if ($this->admin||(($create==0)||(!$this->tableData['columns'][$create-1]['lockaction_insert_after'])&&(($create==$this->columns)||!$this->tableData[$create]['lockaction_insert_before'])))	{
				$err = 0;
				$flexData = $this->flexData;
				foreach ($rows as $row)	{
					$columns = t3lib_div::trimExplode(',', $flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'], 1);
					$new_col = 0;
					for ($x = 1; $x < MAX_COL_ID; $x++)	{
						if (!in_array($x, $columns))	{
							$new_col = $x;
							break;
						}
					}
					if ($new_col)	{
						// Todo:
//						$this->modifyTable_createColumn($row, $new_col);
						array_splice($columns, $create, 0, $new_col);
						$flexData['data']['s_row_'.$row]['lDEF']['columns']['vDEF'] = implode(',', $columns);
						$flexData['data']['s_row_'.$row]['lDEF']['visible_columns']['vDEF'] .= (strlen($this->flexData['data']['s_row_'.$row]['lDEF']['visible_columns']['vDEF'])?',':'').$new_col;
						$sheet = 's_row_'.$row;
						$xmlAr = $this->funcs->getDefaultTable_CellDS($row, $new_col);
						$this->flexDS['sheets'][$sheet]['ROOT']['el'] = array_merge(is_array($this->flexDS['sheets'][$sheet]['ROOT']['el'])?$this->flexDS['sheets'][$sheet]['ROOT']['el']:array(), is_array($xmlAr)?$xmlAr:array());
						$this->flexData = $flexData;
						$this->modified_flexData = true;
					} else	{
						return false;
					}
				}
			}
		}
		return true;
	}

	/****************************************
	 *
	 * RENDERING
	 *
	 * This methods render the Table for BE Editing and also the Cell-Propertied Dialog
	 *
	 ****************************************/

	/**
	 * Renders a RTE field
	 *
	 * @param	array		Cell contents
	 * @return	string		HTML
	 */
	function getRTEContent($cell)	{
		$content = '';
		$db_list = t3lib_div::makeInstance('tx_kbconttable_layout');
		$db_list->previewTextLen = isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['previewTextLen'])?intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['previewTextLen']):100;
		$db_list->backPath = $this->doc->backPath;
		$content .= '<div class="typo3-kbconttable-rte">'.chr(10);
		$row = array('text_align' => $cell['align'], 'text_face' => $cell['fontfamily'], 'text_size' => $cell['fontsize'], 'text_color' => '#'.$cell['color'], 'text_properties' => '');
		$db_list->getProcessedValue('tt_content','text_align,text_face,text_size,text_color,text_properties', $row, $infoArr);
		$content .= $db_list->infoGif($infoArr).$db_list->renderText($cell['rte_content'], $row);
		$content .= '</div>'.chr(10);
		return $content;
	}


	/**
	 * Generate table
	 *
	 * @return	string		HTML
	 */
	function getTable()	{
		$content = '';
		$content .= '<div>
	<a href="#" onClick="if (prepare_form(0)) { return document.editform.submit(); } else { return false; }"><img '.$this->Pic_save.'></a>
	<a href="#" onClick="if (prepare_form(1)) { return document.editform.submit(); } else { return false; }"><img '.$this->Pic_saveAndClose.'></a>
	<a href="#" onClick="if (unsaved_global_content||unsaved_content) { if (confirm(\'There is unsaved Content\nDo you really want to close the Wizard ?\')) { return goto_returnurl(); } { return false; }} else { return goto_returnurl(); }"><img '.$this->Pic_close.'></a>
</div>'.chr(10);
		$content.=t3lib_BEfunc::getFuncCheck(array('id' => $this->id, 'P' => $this->P),'SET[clipBoard]',$this->MOD_SETTINGS['clipBoard'],'index.php','').' '.$this->LANG->getLL('showClipBoard',1).'<br />';

		// Opening Table Tag ---- begin
		$content .= '<table width="100%" cellspacing="'.(intval($this->tableSettings['cellspacing'])>3?intval($this->tableSettings['cellspacing']):3).
			'" cellpadding="'.intval($this->tableSettings['cellpadding']).
			'" border="'.intval($this->tableSettings['border']).'"'.
//			(strlen($this->tableSettings['align'])?' align="'.$this->tableSettings['align'].'"':'').
			(strlen($this->tableSettings['class'])?' class="'.$this->tableSettings['class'].'"':'').
			(strlen($this->tableSettings['id'])?' id="'.$this->tableSettings['id'].'"':'').
			(strlen($this->tableSettings['style'])?' style="'.$this->tableSettings['style'].'"':'').
			(strlen($this->tableSettings['additional'])?' '.$this->tableSettings['additional']:'').'>'.chr(10);
		// Opening Table Tag ---- end
		// Table Header Row ---- begin
		$headerColumnsAr = $this->getTable_Header();
		$headerColumnsStr = implode(chr(10), $headerColumnsAr);
//		$content .= '<tr><td align="center" colspan="'.($this->columns+1).'"><strong>'.$this->LANG->getLL('column_prefix').'</strong></td></tr>'.chr(10);
		$content .= '<tr>'.$headerColumnsStr.'</tr>'.chr(10);
		// Table Header Row ---- end
		// Table Rows ---- begin
		$params = Array(
			'rowData' => Array(),
		);
		list($row_error, $column_error, $error) = $this->funcs->iterateTableData($this->tableSettings, $this->tableData, $this, 'iter_getTable_rowBegin', 'iter_getTable_column', '', '', 'iter_getTable_rowEnd', $params);
		if ($row_error < 0)	{
			if ($column_error < 0)	{
				return Array(-1, $this->error($this->LANG->getLL('error_colrowspan_overlap')));
			}
			return Array(-1, $this->error('Error: Rowspan ranged below end of table.'));
		}
		if ($column_error < 0)	{
			return Array(-1, $this->error('Error: Row-end reached but column expected.'));
		}
		if (strlen($error))	{
			return Array(-1, $this->error($error));
		}
		$content .= implode(chr(10), $params['rowData']);
		// Table Rows ---- end
		// Closing Table Tag ---- begin
		$content .= '</table>'.chr(10);
		// Closing Table Tag ---- end
		return Array(0, $content);
	}

	/**
	 * Generate table header
	 *
	 * @return	string		HTML
	 */
	function getTable_Header()	{
		$hiddenColumns = t3lib_div::trimExplode(',', $this->tableSettings['hidden_columns'], 1);
		$headerColumnsAr = Array();
		$headerColumnsAr[] = '<th class="typo3-kbconttable-lefttop">&nbsp;</th>';
		$hiddenColumns = t3lib_div::trimExplode(',', $this->tableSettings['hidden_columns'], 1);
		for ($column = 1; $column <= $this->columns; $column++)	{
			$headerColumnsAr[] = '<th class="typo3-kbconttable-header">
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td width="33%" height="50%" class="bgColor2'.(in_array($column, $hiddenColumns)?'-hidden':'').'" align="left">
				'.(($column>1)&&(!$this->tableData['columns'][$column-1]['lockaction_move_left'])?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[move_column_left]' => $column)).'"><img '.$this->Pic_moveLeft.' alt="Move left"></a>':'&nbsp;').'
			</td>
			<td width="33%" height="50%" class="bgColor2'.(in_array($column, $hiddenColumns)?'-hidden':'').'" align="center">
				'.$column.'
			</td>
			<td width="33%" height="50%" class="bgColor2'.(in_array($column, $hiddenColumns)?'-hidden':'').'" align="right">
				'.(($column<$this->columns)&&(!$this->tableData['columns'][$column-1]['lockaction_move_right'])?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[move_column_right]' => $column)).'"><img '.$this->Pic_moveRight.' alt="Move right"></a>':'&nbsp;').'
			</td>
		</tr>
		<tr>
			<td width="33%" height="50%" align="left" class="bgColor5'.(in_array($column, $hiddenColumns)?'-hidden':'').'">
				'.(!$this->tableData['columns'][$column-1]['lockaction_insert_before']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[create_column]' => ($column-1))).'"><img '.$this->Pic_create.' alt="Insert column before" style="margin: 5px;"></a>':'').'
			</td>
			<td width="33%" height="50%" align="center" class="bgColor5'.(in_array($column, $hiddenColumns)?'-hidden':'').'" nowrap>
				'.(!$this->tableData['columns'][$column-1]['lockaction_hide']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this-> P, 'kbconttable' => '', 'kbconttable[hide_column]' => $column)).'">'.(in_array($column, $hiddenColumns)?'<img '.$this->Pic_unhide.' alt="Un-hide column" style="margin: 5px;">':'<img '.$this->Pic_hide.' alt="Hide column" style="margin: 5px;">').'</a>':'').
				(!$this->tableData['columns'][$column-1]['lockaction_delete']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[delete_column]' => $column)).'"><img '.$this->Pic_delete.' alt="Delete column" style="margin: 5px;"></a>':'').'
			</td>
			<td width="33%" height="50%"align="right" class="bgColor5'.(in_array($column, $hiddenColumns)?'-hidden':'').'">
				'.(!$this->tableData['columns'][$column-1]['lockaction_insert_after']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[create_column]' => $column)).'"><img '.$this->Pic_create.' alt="Insert column after" style="margin: 5px;"></a>':'').'
			</td>
		</tr>
	</table>
</th>';
		}
		return $headerColumnsAr;
	}

	/**
	 * Generate Cell Edit Formular (possibly containing RTE)
	 *
	 * @return	string		HTML
	 */
	function getCellEdit()	{
		if (intval($this->flexData['data']['sDEF']['lDEF']['rte_mode']['vDEF'])&&$this->rteMode!='none')	{
			$fakePA = array(
				'fieldConf' => array(
					'config' => array(
						'cols' => 30,
						'rows' => 5,
					),
				),
				'extra' => strlen($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['RTEconf'])?$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['RTEconf']:'richtext[cut|copy|paste|formatblock|textcolor|bold|italic|underline|left|center|right|orderedlist|unorderedlist|outdent|indent|link|table|image|line|chMode]:rte_transform[mode=ts_css|imgpath=uploads/tx_kbconttable/rte/]',
				'fieldChangeFunc' => array(),
				'itemFormElName' => 'rte_content',
				'itemFormElValue' => '',
			);
			$fakeRow = array(
				'bodytext' => '',
				'uid' => $this->P ['uid'],
				'pid' => $this->P['pid']
			);
			$specConf = $this->tceforms->getSpecConfFromString($fakePA['extra'], $fakePA['fieldConf']['defaultExtras']);
			$RTEsetup = $GLOBALS['BE_USER']->getTSConfig('RTE',t3lib_BEfunc::getPagesTSconfig($this->P['pid']));
			$thisConfig = t3lib_BEfunc::RTEsetup($RTEsetup['properties'],'tt_content','rte_content','text');
		}
		$content = '';
		$content .= '<div>
	<a href="#" onClick="return save_celledit();"><img '.$this->Pic_save.' style="display: block; float: left;"></a>
	<a href="#" onFocus="if (save_celledit()) { return close_celledit(); }"><img '.$this->Pic_saveAndClose.' style="display: block; float: left;"></a>
	<a href="#" onClick="if (unsaved_content) { if (confirm(\'There is unsaved Content\nDo you really want to close the cell properties ?\')) { return close_celledit(); } { return false; }} else { return close_celledit(); }"><img '.$this->Pic_close.' style="display: block; float: left; "></a>
</div>'.chr(10);
		$content .= '
<table cellspacing="8" cellpadding="0" border="0">
	<tr>
		<td colspan="4" class="typo3-kbconttable-celabel">
			<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[reset_colrowspan]' => 1)).'">Reset all Colspan and Rowspans</a>
		</td>
	</tr>
	<tr>
		<td class="typo3-kbconttable-celabel">
			Colspan :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<input type="text" name="colspan" value="" style="width: 40px;" onChange="unsaved_content = 1;" />
		</td>
		<td class="typo3-kbconttable-celabel">
			Rowspan :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<input type="text" name="rowspan" value="" style="width: 40px;" onChange="unsaved_content = 1;" />
		</td>
	</tr>
	<tr>
		<td class="typo3-kbconttable-celabel">
			Celltype :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<select name="celltype" size="1" style="width: 40px;" onChange="unsaved_content = 1;">
				<option value="td">td</option>
				<option value="th">th</option>
			</select>
		</td>
		<td class="typo3-kbconttable-celabel">
			Wordwrap :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<select name="wordwrap" size="1" style="width: 80px;" onChange="unsaved_content = 1;">
				<option value="">Normal</option>
				<option value="nowrap">no-wrap</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="typo3-kbconttable-celabel">
			Cellwidth :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<input type="text" name="cellwidth" value="" style="width: 40px;"  onChange="unsaved_content = 1;"/>
			<select name="cellwidth_format" size="1" style="width: 80px;" onChange="unsaved_content = 1;">
				<option value="">Pixel</option>
				<option value="%">Percent</option>
			</select>
		</td>
		<td class="typo3-kbconttable-celabel">
			Cellheight :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<input type="text" name="cellheight" value="" style="width: 40px;" onChange="unsaved_content = 1;" />
			<select name="cellheight_format" size="1" style="width: 80px;" onChange="unsaved_content = 1;">
				<option value="">Pixel</option>
				<option value="%">Percent</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="typo3-kbconttable-celabel">
			Align :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<select name="align" size="1" style="width: 80px;" onChange="unsaved_content = 1;">
				<option value="">Normal</option>
				<option value="left">Left</option>
				<option value="center">Center</option>
				<option value="right">Right</option>
			</select>
		</td>
		<td class="typo3-kbconttable-celabel">
			Vertical Align :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<select name="valign" size="1" style="width: 80px;" onChange="unsaved_content = 1;">
				<option value="">Normal</option>
				<option value="top">Top</option>
				<option value="middle">Middle</option>
				<option value="bottom">Bottom</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="typo3-kbconttable-celabel">
			Background Color :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<input type="text" name="backgroundcolor" value="" style="width: 40px;" maxlength="6" onChange="unsaved_content = 1;" />
		</td>
		<td class="typo3-kbconttable-celabel">
			Text Color :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<input type="text" name="color" value="" style="width: 40px;" maxlength="6" onChange="unsaved_content = 1;" />
		</td>
	</tr>
	<tr>
		<td class="typo3-kbconttable-celabel">
			Class :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<input type="text" name="cl_val" value="" style="width: 80px;" onChange="unsaved_content = 1;" />
		</td>
		<td class="typo3-kbconttable-celabel">
			Id :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<input type="text" name="id_val" value="" style="width: 80px;" onChange="unsaved_content = 1;" />
		</td>
	</tr>
	<tr>
		<td class="typo3-kbconttable-celabel">
			Fontsize :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<input type="text" name="fontsize" value="" style="width: 40px;" onChange="unsaved_content = 1;" />
			<select name="fontsize_format" size="1" style="width: 80px;" onChange="unsaved_content = 1;">
				<option value="px">px</option>
				<option value="pt">pt</option>
			</select>
		</td>
		<td class="typo3-kbconttable-celabel">
			Fontweight :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<select name="fontweight" size="1" style="width: 80px;" onChange="unsaved_content = 1;">
				<option value="">Normal</option>
				<option value="bold">Bold</option>
				<option value="bolder">Bolder</option>
				<option value="lighter">Lighter</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="typo3-kbconttable-celabel">
			Fontfamily :
		</td>
		<td class="typo3-kbconttable-ceinput">
			<input type="text" name="fontfamily" value="" style="width: 120px;" onChange="unsaved_content = 1;" />
		</td>
		<td colspan="2" class="typo3-kbconttable-celabel">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td class="typo3-kbconttable-celabel">
			Style :
		</td>
		<td colspan="3" class="typo3-kbconttable-ceinput">
			<textarea name="style_val" style="width: 300px; height: 50px;" onChange="unsaved_content = 1;"></textarea>
		</td>
	</tr>
	<tr>
		<td class="typo3-kbconttable-celabel">
			Additional Attributes :
		</td>
		<td colspan="3" class="typo3-kbconttable-ceinput">
			<textarea name="additional" style="width: 300px; height: 50px;" onChange="unsaved_content = 1;"></textarea>
		</td>
	</tr>
	'.(intval($this->flexData['data']['sDEF']['lDEF']['rte_mode']['vDEF'])?'
	<tr>
		<td class="typo3-kbconttable-celabel">
			Content :
		</td>
		<td colspan="3" class="typo3-kbconttable-ceinput">
			'.(($this->rteMode=='none')?'<textarea name="rte_content" id="rte_content" style="width: 300px; height: 200px;"></textarea>':$this->rteObj->drawRTE($this->tceforms, 'tt_content', 'rte_content', $fakeRow, $fakePA, $specConf, $thisConfig, 'text', $this->doc->backPath.'../', $this->P['pid'])).'
		</td>
	</tr>
	':'').'
</table>';
		return $content;
	}

	/**
	 * Generate hidden GET params array
	 *
	 * @param	array		GET parameters
	 * @param	string		Label of the GET parameters
	 * @return	string		required hidden fields
	 */
	function getHiddenArray($ar, $Glabel)	{
		$content = '';
		if (is_array($ar))	{
			foreach ($ar as $label => $val)	{
				$content .= '<input type="hidden" name="'.$Glabel.'['.$label.']" value="'.htmlspecialchars($val).'">'.chr(10);
			}
		}
		return $content;
	}

	/**
	 * Generate hidden form
	 *
	 * @return	string		required hidden fields
	 */
	function getHiddenForm()	{
		$content = '';
		$content .= '<input type="hidden" name="kbconttable[close]" value="0">'.chr(10);
		$content .= $this->getHiddenArray($this->P, 'P');
		$params = Array(
			'content' => &$content,
		);
		list($row, $column, $err) = $this->funcs->iterateTableData($this->tableSettings, $this->tableData, $this, '', 'iter_getHiddenForm_column', '', '', '', $params);
		if ($row < 0)	{
			if ($column < 0)	{
				return Array(-1, $this->error($this->LANG->getLL('error_colrowspan_overlap').'<br /><br /><a href="'.t3lib_div::linkThisScript(Array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[reset_colrowspan]' => 1)).'">'.$this->LANG->getLL('reset_colrowspan').'</a>'));
			}
			return Array(-1, $this->error('Error: Rowspan ranged below end of table.', 0, $err));
		}
		if ($column < 0)	{
			return Array(-1, $this->error('Error: Row-end reached but column expected.', 0, $err));
		}
		if (strlen($err))	{
			return Array(-1, $this->error($err));
		}
		return Array(0, $params['content']);
	}

	/**
	 * JS generation method
	 *
	 * @return	string		Required JS
	 */
	function getJS()	{
		$content  .= '<script language="JavaScript" type="text/javascript" src="jsfunc.js"></script>';
		$content  .= $this->doc->wrapScriptTags('
'.$this->doc->redirectUrls().'
function goto_returnurl() {
	document.location.href = "'.$this->P['returnUrl'].'";
	return false;
}
function jumpToUrl(URL)	{	//
	document.location = URL;
	return false;
}
function jumpExt(URL,anchor)	{	//
	var anc = anchor?anchor:"";
	document.location = URL+(T3_THIS_LOCATION?"&returnUrl="+T3_THIS_LOCATION:"")+anc;
	return false;
}
function jumpSelf(URL)	{	//
	document.location = URL+(T3_RETURN_URL?"&returnUrl="+T3_RETURN_URL:"");
	return false;
}
function editRecords(table,idList,addParams,CBflag)	{	//
	document.location="'.$backPath.'alt_doc.php?returnUrl='.rawurlencode(t3lib_div::getIndpEnv('REQUEST_URI')).
		'&edit["+table+"]["+idList+"]=edit"+addParams;
}
function editList(table,idList)	{	//
	var list="";

		// Checking how many is checked, how many is not
	var pointer=0;
	var pos = idList.indexOf(",");
	while (pos!=-1)	{
		if (cbValue(table+"|"+idList.substr(pointer,pos-pointer))) {
			list+=idList.substr(pointer,pos-pointer)+",";
		}
		pointer=pos+1;
		pos = idList.indexOf(",",pointer);
	}
	if (cbValue(table+"|"+idList.substr(pointer))) {
		list+=idList.substr(pointer)+",";
	}

	return list ? list : idList;
}

if (top.fsMod) top.fsMod.recentIds["web"] = '.intval($this->id).';
');
		return $content;
	}



	/**
	 * Error method
	 *
	 * @param	string		Error string
	 * @param	integer		If true not stored message gets shown
	 * @return	void
	 */
	function error($error, $not_stored = 0, $info = '')	{
		$backtrace = debug_backtrace();
		$caused_line = $backtrace[0];
		$caused_method = $backtrace[1];
		$location = $this->LANG->getLL('error_location');
		$location = str_replace('###FILE###', basename($caused_method['file']), $location);
		$location = str_replace('###LINE###', $caused_line['line'], $location);
		$location = str_replace('###CLASS###', $caused_method['class'], $location);
		$location = str_replace('###FUNCTION###', $caused_method['function'], $location);
		$content = '<h1>'.$this->LANG->getLL('error_header').'</h1>
	<p>
		<h4>'.$error.' ('.$location.')</h4>
	</p>
	'.($not_stored?'<p>
	</p>
		<br />
		'.$this->LANG->getLL('error_msg_notstored').'
	':'
	<p>
	</p>
	<p>
		<br />
		'.$this->LANG->getLL('error_msg').'
	</p>');
		return $content;
	}

	/****************************************
	 *
	 * CHECKING
	 *
	 * This methods perform checks on data
	 *
	 ****************************************/

	/**
	 * Checks if row and columns are correct (not invalid row/colspan)
	 *
	 * @param	integer		Defines if not stored message should get outputted.
	 * @return	array		(content, error string)
	 */
	function checkRowColumnCount($validate = 0)	{
		$params = Array(
			'columns' => 0,
		);
		list($this->rows, $this->columns, $err) = $this->funcs->iterateTableData($this->tableSettings, $this->tableData, $this, '', '', '', '', 'iter_chkRowColCount_rowEnd', $params);
		$content = '';
		$err = 0;
		if ($this->rows === -1)	{
			if ($this->columns === -1)	{
				$content .= $this->error($this->LANG->getLL('error_colrowspan_overlap'), $validate);
			} else	{
				$content .= $this->error($this->LANG->getLL('error_rowcount'), $validate);
			}
			$err = 1;
		} else if ($this->cols === -1)	{
			$content .= $this->error($this->LANG->getLL('error_colcount'), $validate);
			$err = 1;
		} else if (strlen($error))	{
			$content .= $this->error($error, $validate);
			$err = 1;
		}
		return Array($content, $err);
	}


	/****************************************
	 *
	 * ITERATION METHODS
	 *
	 * This methods are used for iterating over the $this->tableData Array which
	 * is a internal represenation of the table.
	 *
	 ****************************************/


	/**
	 * Iteration method. Generates HTML for row begin.
	 *
	 * @param	array		Parameters
	 * @return	array		(rows, column, error string)
	 */
	function iter_getTable_rowBegin(&$params)	{
		$params['columnData'] = Array();
		$params['columnData'][] = '<th class="typo3-kbconttable-header-row" width="40"><table cellspacing="0" cellpadding="0" border="0" width="40" height="100%">
	<tr>
		<td class="bgColor2'.(!$params['rowAr']['visible']?'-hidden':'').'" width="20" align="center" align="center" valign="top">
			'.($params['rows']&&(!$this->tableData[$params['rows']]['lockaction_move_up'])?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[move_row_up]' => ($params['rows']+1))).'"><img '.$this->Pic_moveUp.' alt="Move up"></a>':'&nbsp;').'
		</td>
		<td class="bgColor5'.(!$params['rowAr']['visible']?'-hidden':'').'" width="20" align="center" valign="top">
				'.(!$this->tableData[$params['rows']]['lockaction_insert_before']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[create_row]' => $params['rows'])).'"><img '.$this->Pic_create.' alt="Insert row before" style="margin: 3px;"></a>':'').'
		</td>
	</tr>
	<tr>
		<td class="bgColor2'.(!$params['rowAr']['visible']?'-hidden':'').'" width="20" align="center" valign="middle">
			'.($params['rows']+1).'
		</td>
		<td class="bgColor5'.(!$params['rowAr']['visible']?'-hidden':'').'" width="20" align="center" valign="middle">
			'.(!$this->tableData[$params['rows']]['lockaction_hide']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[hide_row]' => ($params['rows']+1))).'">'.($params['rowAr']['visible']?'<img '.$this->Pic_hide.' alt="Hide row" style="margin: 5px;">':'<img '.$this->Pic_unhide.' alt="Un-hide row" style="margin: 5px;">').'</a>':'').'
			'.(!$this->tableData[$params['rows']]['lockaction_delete']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[delete_row]' => ($params['rows']+1))).'"><img '.$this->Pic_delete.' alt="Delete row" style="margin: 5px;"></a>':'').'
		</td>
	</tr>
	<tr>
		<td class="bgColor2'.(!$params['rowAr']['visible']?'-hidden':'').'" width="20" align="center" valign="bottom">
			'.((($params['rows']+1)<$params['rowsMax'])&&(!$this->tableData[$params['rows']]['lockaction_move_down'])?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[move_row_down]' => ($params['rows']+1))).'"><img '.$this->Pic_moveDown.' alt="Move down"></a>':'&nbsp;').'
		</td>
		<td class="bgColor5'.(!$params['rowAr']['visible']?'-hidden':'').'" width="20" align="center" valign="bottom">
				'.(!$this->tableData[$params['rows']]['lockaction_insert_after']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[create_row]' => $params['rows']+1)).'"><img '.$this->Pic_create.' alt="Insert row after" style="margin: 3px;"></a>':'').'
		</td>
	</tr>
			</table></th>';
		return Array(0, 0, '');
	}


	/**
	 * Iteration method. Generates HTML for column (cell).
	 *
	 * @param	array		Parameters
	 * @return	array		(rows, column, error string)
	 */
	function iter_getTable_column(&$params)	{
		$columnAr = $params['columnAr'];
		$cell_render = t3lib_div::makeInstance('tx_kbconttable_berenderCE');
		$cell_render->init($this);
		$ret = '<'.
			// Attributes
			(strlen($columnAr['celltype'])?$columnAr['celltype']:'td').
			((intval($columnAr['colspan'])>1)?' colspan="'.intval($columnAr['colspan']).'"':'').
			((intval($columnAr['rowspan'])>1)?' rowspan="'.intval($columnAr['rowspan']).'"':'').
			((intval($columnAr['cellwidth'])!=0)?' width="'.intval($columnAr['cellwidth']).$columnAr['cellwidth_format'].'"':'').
			((intval($columnAr['cellheight'])!=0)?' height="'.intval($columnAr['cellheight']).$columnAr['cellheight_format'].'"':'').
			(strlen($columnAr['align'])?' align="'.$columnAr['align'].'"':'').
			(strlen($columnAr['valign'])?' valign="'.$columnAr['valign'].'"':' valign="top"').
			(strlen($columnAr['wordwrap'])?' '.$columnAr['wordwrap']:'').
			(strlen($columnAr['backgroundcolor'])==6?' bgcolor="#'.$columnAr['backgroundcolor'].'"':'').
			(strlen($columnAr['additional'])?' '.$columnAr['additional']:'').
			//	Style
			(strlen($columnAr['fontweight'])||strlen($columnAr['fontfamily'])||
			intval($columnAr['fontsize'])||strlen($columnAr['style'])
			?' style="'.
			(strlen($columnAr['fontweight'])?' font-weight: '.$columnAr['fontweight'].';':'').
			(strlen($columnAr['fontfamily'])?' font-family: '.$columnAr['fontfamily'].';':'').
			(strlen($columnAr['color'])==6?' color: #'.$columnAr['color'].';':'').
			(intval($columnAr['fontsize'])?' font-size: '.intval($columnAr['fontsize']).(strlen($columnAr['fontsize_format'])?$columnAr['fontsize_format']:'px').';':'').
			(strlen($columnAr['style'])?' '.str_replace(chr(10), ' ', $columnAr['style']).';':'').'"'
			:'').
			// Class and Id
			(strlen($columnAr['class'])?' class="'.$columnAr['class'].'"':'').
			(strlen($columnAr['id'])?' id="'.$columnAr['id'].'"':'').
	'>';
	$header = '<div class="typo3-kbconttable-cellheader'.(!$params['rowAr']['visible']||$columnAr['hidden']||!$columnAr['visible']?'-hidden':'').'">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td width="33%" align="left">
					'.($params['cols']&&!$this->tableData[$params['row']][$params['col']]['lockaction_move_left_cell']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[move_cell_left]' => ($params['rows']+1).','.($params['cols']+1))).'"><img '.$this->Pic_moveLeft.' alt="Move left"></a>':'&nbsp;').'
				</td>
				<td width="33%" align="center" nowrap>
					'.($params['rows']&&!$this->tableData[$params['row']][$params['col']]['lockaction_move_up_cell']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[move_cell_up]' => ($params['rows']+1).','.($params['cols']+1))).'"><img '.$this->Pic_moveUp.' alt="Move up"></a>':'&nbsp;').'
				'.(!$this->tableData[$params['row']][$params['col']]['lock_props']?'<a href="#cellprops" onClick="if (unsaved_content) { if (confirm(\'There is unsaved Content\nDo you really want to close the cell properties ?\')) { return show_celledit('.($params['rows']+1).','.($params['cols']+1).'); } else { return false; } } else { return show_celledit('.($params['rows']+1).','.($params['cols']+1).'); }"><img '.$this->Pic_edit.'></a>':'').'
				'.(!$this->tableData[$params['row']][$params['col']]['lockaction_hide_cell']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[hide_cell]' => ($params['rows']+1).','.($params['cols']+1))).'">'.(!$params['columnAr']['visible']?'<img '.$this->Pic_unhide.' alt="Un-hide column">':'<img '.$this->Pic_hide.' alt="Hide column">').'</a>':'').'
					'.((($params['rows']+1)<$params['rowsMax'])&&!$this->tableData[$params['row']][$params['col']]['lockaction_move_down_cell']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[move_cell_down]' => ($params['rows']+1).','.($params['cols']+1))).'"><img '.$this->Pic_moveDown.' alt="Move down"></a>':'&nbsp;').'
				</td>
				<td width="33%" align="right">
					'.((($params['cols']+1)<$this->columns)&&!$this->tableData[$params['row']][$params['col']]['lockaction_move_right_cell']?'<a href="'.t3lib_div::linkThisScript(array('CB' => '', 'SET' => '', 'P' => $this->P, 'kbconttable' => '', 'kbconttable[move_cell_right]' => ($params['rows']+1).','.($params['cols']+1))).'"><img '.$this->Pic_moveRight.' alt="Move right"></a>':'&nbsp;').'
				</td>
			</tr>
		</table>
	</div>';
	if (intval($this->flexData['data']['sDEF']['lDEF']['rte_mode']['vDEF']))	{
		$rte = $this->getRTEContent($columnAr);
		$close_tag = '</'.(strlen($columnAr['celltype'])?$columnAr['celltype']:'td').'>';
		$ret .= $header.$rte.$close_tag;
	} else	{
		$ret .= $cell_render->main($columnAr['elements'], $params['rows']+1, $params['cols']+1, $params['row'], $params['col'], $header).'</'.(strlen($columnAr['celltype'])?$columnAr['celltype']:'td').'>';
	}
		$params['columnData'][] = $ret;
		return Array(0, 0, '');
	}

	/**
	 * Iteration method. Generates HTML for <tr> tag.
	 *
	 * @param	array		Parameters
	 * @return	array		(rows, column, error string)
	 */
	function iter_getTable_rowEnd(&$params)	{
		$params['rowData'][] = '<tr>'.implode(chr(10), $params['columnData']).'</tr>';
		return Array(0, 0, '');
	}

	/**
	 * Iteration method. Checks if correct amount of columns was in this row
	 *
	 * @param	array		Parameters
	 * @return	array		(rows, column, error string)
	 */
	function iter_chkRowColCount_rowEnd(&$params)	{
		if ($params['columns'] === 0)	{
			$params['columns'] = $params['cols'];
		} else	{
			if ($params['columns'] !== $params['cols'])	{
				// Column count in two different rows are different
				return Array(0, -1, 'Column count different in two distinct rows.');
			}
		}
	}

	/**
	 * Iteration method. Generates the HTML for the hidden input fields containg the table data
	 *
	 * @param	array		Parameters
	 * @return	array		(rows, column, error string)
	 */
	function iter_getHiddenForm_column(&$params)	{
		foreach ($params['columnAr'] as $element => $elementAr)	{
			if (strpos($element, 'lockaction_')===0)	continue;
			if (strpos($element, 'lock_')===0)	continue;
			switch ($element)	{
				case 'rowspan':
				case 'colspan':
					$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).']['.$element.']" value="'.((intval($params['columnAr'][$element])>1)?intval($params['columnAr'][$element]):1).'">'.chr(10);
					$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).'][lock_'.$element.']" value="'.((intval($params['columnAr']['lock_'.$element])<0)?0:1).'">'.chr(10);
				break;
				case 'cellwidth':
				case 'cellheight':
				case 'fontsize':
					$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).']['.$element.']" value="'.((intval($params['columnAr'][''.$element.''])!=0)?intval($params['columnAr'][''.$element.'']):0).'">'.chr(10);
					$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).'][lock_'.$element.']" value="'.((intval($params['columnAr']['lock_'.$element])<0)?0:1).'">'.chr(10);
				break;
				case 'celltype':
					$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).']['.$element.']" value="'.(strlen($params['columnAr'][$element])?$params['columnAr'][$element]:'td').'">'.chr(10);
					$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).'][lock_'.$element.']" value="'.((intval($params['columnAr']['lock_'.$element])<0)?0:1).'">'.chr(10);
				break;
				case 'wordwrap':
				case 'cellwidth_format':
				case 'cellheight_format':
				case 'align':
				case 'valign':
				case 'fontsize_format':
				case 'fontweight':
					$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).']['.$element.']" value="'.$params['columnAr'][$element].'">'.chr(10);
					$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).'][lock_'.$element.']" value="'.((intval($params['columnAr']['lock_'.$element])<0)?0:1).'">'.chr(10);
				break;
				case 'backgroundcolor':
				case 'color':
				case 'class':
				case 'id':
				case 'fontfamily':
				case 'style':
				case 'additional':
					$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).']['.$element.']" value="'.$params['columnAr'][$element].'">'.chr(10);
					$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).'][lock_'.$element.']" value="'.(intval($params['columnAr']['lock_'.$element])==-1?0:1).'">'.chr(10);
				break;
				case 'rte_content':
				case 'visible':
				case 'hidden':
				case 'elements':
				case 'fastprops':
				break;
				default:
					echo 'Unconfigured field "'.$element.'" for iter_getHiddenForm_column.<br>'.chr(10);
				break;
			}
		}
		if (intval($this->flexData['data']['sDEF']['lDEF']['rte_mode']['vDEF']))	{
			$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).'][rte_content]" value="'.htmlentities($params['columnAr']['rte_content'], ENT_COMPAT, $GLOBALS['LANG']->charSet).'">'.chr(10);
			$params['content'] .= '<input type="hidden" name="kbconttable[data]['.($params['rows']+1).']['.($params['cols']+1).'][lock_rte_content]" value="'.($params['columnAr']['lock_content']?1:0).'">'.chr(10);
		}
		return Array(0, 0, '');
	}

	/****************************************
	 *
	 * CONTENT ELEMENTS OPERATIONS
	 *
	 * Operations via the templavoila xmlrelhandler
	 *
	 ****************************************/

	/**
	 * Initiates processing for creating a new record.
	 *
	 * @param	string		$parentRecord:
	 * @param	array		$defVals: Array containing default values for the new record, e.g. [tt_content][CType] = 'text'
	 * @return	void
	 * @see		insertRecord ()
	 */
	function cmd_createNewRecord ($parentRecord, $defVals='')	{
			// Historically "defVals" has been used for submitting the row data. We still use it and use it for our new row:
		$defVals = (string)$defVals == '' ? t3lib_div::_GP('defVals') : $defVals;
		$row = $defVals['tt_content'];
			// Set default colpos value
		$row['colPos'] = $this->colPos;
			// Create new record and open it for editing
		$newUid = $this->xmlhandler->insertRecord($parentRecord, $row);
		$location = $GLOBALS['BACK_PATH'].'alt_doc.php?edit[tt_content]['.$newUid.']=edit&returnUrl='.rawurlencode(t3lib_extMgm::extRelPath('kb_conttable').'tt_content_tx_kbconttable_flex_ds/index.php?'.$this->funcs->linkParams());
		header('Location: '.$location);
	}

	/**
	 * Initiates processing for unlinking a record.
	 *
	 * @param	string		$unlinkRecord: The element to be unlinked.
	 * @return	void
	 * @see		pasteRecord ()
	 */
	function cmd_unlinkRecord ($unlinkRecord)	{
		$this->xmlhandler->pasteRecord('unlink', $unlinkRecord, '');
		header('Location: '.t3lib_div::locationHeaderUrl('index.php?'.$this->funcs->linkParams()));
	}

	/**
	 * Gets executed when a record shall get deleted.
	 *
	 * @param	string		Unlink element XML Path
	 * @return	void
	 */
	function cmd_deleteRecord ($deleteRecord)	{
		$this->xmlhandler->pasteRecord('delete', $deleteRecord, '');
		header('Location: '.t3lib_div::locationHeaderUrl('index.php?'.$this->funcs->linkParams()));
	}

	/**
	 * Initiates processing for making a local copy of a record.
	 *
	 * @param	string		$unlinkRecord: The element to be copied to current page.
	 * @return	void
	 * @see		pasteRecord ()
	 */
	function cmd_makeLocalRecord ($makeLocalRecord)	{
		$this->xmlhandler->pasteRecord('localcopy', $makeLocalRecord, '');
//		header('Location: '.t3lib_div::locationHeaderUrl('index.php?'.$this->funcs->linkParams()));
	}
	
	/****************************************
	 *
	 * FAST MODE
	 *
	 * Methods required for fast mode
	 *
	 ****************************************/
	
	/**
	 * Filters out uneeded fields of the Flexdata array before storing depending on if we are in fast-mode or not.
	 *
	 * @return	void
	 */
	function filter_unneededFlexData($flexData)	{
		$rows = t3lib_div::trimExplode(',', $this->tableSettings['rows'], 1);
		if (is_array($flexData['data']))	{
			foreach ($flexData['data'] as $sheet => $sheetArr)	{
				$parts = explode('_', $sheet);
				if ((count($parts)!==3)||($parts[0]!=='s')||($parts[1]!=='row'))	{
					continue;
				}
				if (!in_array($parts[2], $rows))	{
						// Row not valid
					unset($flexData['data'][$sheet]);
					continue;
				}
				$row = $parts[2];
				$columns = t3lib_div::trimExplode(',', $sheetArr['lDEF']['columns']['vDEF'], 1);
				foreach ($sheetArr['lDEF'] as $field => $fieldArr)	{
					$parts = explode('_', $field, 4);
					if ((count($parts)!==4)||($parts[0]!=='column'))	{
						continue;
					}
					if ($parts[1]!=$row)	{
							// Field doesn't belong into this sheet
						unset($flexData['data'][$sheet]['lDEF'][$field]);
						continue;
					}
					if (!in_array($parts[2], $columns))	{
							// Column not valid
						unset($flexData['data'][$sheet]['lDEF'][$field]);
						continue;
					}
					$key = $parts[3];
					if ($this->fastMode)	{
						if (($key!='fastprops')&&($key!='rte_content')&&($key!='elements'))	{
							unset($flexData['data'][$sheet]['lDEF'][$field]);
							continue;
						}
					} else	{
						if ($key=='fastprops')	{
							unset($flexData['data'][$sheet]['lDEF'][$field]);
							continue;
						}
					}
				}
			}
		}
		return $flexData;
	}
	
	
	/**
	 * Filters out uneeded fields of the Flex DS array before storing depending on if we are in fast-mode or not.
	 *
	 * @return	void
	 */
	function filter_unneededFlexDS($flexDS, $flexData)	{
		$rows = t3lib_div::trimExplode(',', $this->tableSettings['rows'], 1);
		if (is_array($flexDS['sheets']))	{
			foreach ($flexDS['sheets'] as $sheet => $sheetArr)	{
				$parts = explode('_', $sheet);
				if ((count($parts)!==3)||($parts[0]!=='s')||($parts[1]!=='row'))	{
					continue;
				}
				if (!in_array($parts[2], $rows))	{
						// Row not valid
					unset($flexDS['sheet'][$sheet]);
					continue;
				}
				$row = $parts[2];
				$columns = t3lib_div::trimExplode(',', $flexData['data'][$sheet]['lDEF']['columns']['vDEF'], 1);
				$DSArr = array();
				foreach ($columns as $col)	{
					if ($this->fastMode)	{
						$xml = $this->funcs->defaultCellDS_fast();
					} else	{
						$xml = $this->funcs->defaultCellDS_normal();
					}
					$xml = str_replace('###ROW###', $row, $xml);
					$xml = str_replace('###COLUMN###', $col, $xml);
					$xmlAr = t3lib_div::xml2array($xml);
					$xmlAr = $this->funcs->columnLabel($xmlAr, $col);
					$DSArr = array_merge($DSArr, $xmlAr);
				}
				$flexDS['sheets'][$sheet]['ROOT']['el'] = array_merge($flexDS['sheets'][$sheet]['ROOT']['el'], $DSArr);
				foreach ($sheetArr['ROOT']['el'] as $field => $fieldArr)	{
					$parts = explode('_', $field, 4);
					if ((count($parts)!==4)||($parts[0]!=='column'))	{
						continue;
					}
					if ($parts[1]!=$row)	{
							// Field doesn't belong into this sheet
						unset($flexDS['sheets'][$sheet]['ROOT']['el'][$field]);
						continue;
					}
					if (!in_array($parts[2], $columns))	{
							// Column not valid
						unset($flexDS['sheets'][$sheet]['ROOT']['el'][$field]);
						continue;
					}
					$key = $parts[3];
					if ($this->fastMode)	{
						if (($key!='fastprops')&&($key!='rte_content')&&($key!='elements'))	{
							unset($flexDS['sheets'][$sheet]['ROOT']['el'][$field]);
							continue;
						}
					} else	{
						if ($key=='fastprops')	{
							unset($flexDS['sheets'][$sheet]['ROOT']['el'][$field]);
							continue;
						}
					}
				}
			}
		}
		return $flexDS;
	}

	/****************************************
	 *
	 * SUPPORTING METHODS
	 *
	 * This are some used methods.
	 *
	 ****************************************/

	/**
	 * Swaps two parts of an array
	 *
	 * @param	array		The array in which parts should be swapped
	 * @param	integer		Start of first part
	 * @param	integer		Length of first part
	 * @param	integer		Start of second part
	 * @param	integer		Length of second part
	 * @return	array		The resulting array
	 */
	function array_swap($array, $start1, $length1, $start2, $length2)	{
		$a0 = array_slice($array, 0, $start1);
		$a1 = array_slice($array, $start1, $length1);
		$a2 = array_slice($array, $start1+$length1, $start2-($start1+$length1));
		$a3 = array_slice($array, $start2, $length2);
		$a4 = array_slice($array, $start2+$length2);
		return array_merge($a0, $a3, $a2, $a1, $a4);
	}


} // EOF: class



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/tt_content_tx_kbconttable_flex_ds/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/tt_content_tx_kbconttable_flex_ds/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_kbconttable_tt_content_tx_kbconttable_flex_dswiz');
$SOBE->init();


$SOBE->main();
$SOBE->printContent();

?>
