<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004-2009 Bernhard Kraft (kraftb@think-open.at)
*  All rights reserved
*  based on code by :
*  (c) 2003, 2004  Kasper Skårhøj (kasper@typo3.com) / Robert Lemke (robert@typo3.org)
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
 * Class for rendering content elements with Templa voila code
 *
 * $Id$
 *
 * @author	Bernhard Kraft <kraftb@think-open.at>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   53: class tx_kbconttable_berenderCE
 *   64:     function init(&$calling_obj)
 *   91:     function main($elements, $row_idx, $col_idx, $row, $col, $header)
 *  235:     function TV_flexGen($row, $col, $header)
 *  319:     function renderFrameWork($dsInfo, $clipboardElInPath, $referenceInPath, $counter = 0)
 *  425:     function linkMakeLocal($str, $makeLocalRecord)
 *  440:     function linkUnlink($str, $unlinkRecord, $realDelete=FALSE)
 *  458:     function linkEdit($str, $table, $uid)
 *  471:     function linkNew($str, $parentRecord)
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_kbconttable_berenderCE	{
	var $calling_obj = NULL;
	var $global_tt_content_elementRegister=array(); // Contains a list of all content elements which are used on the page currently being displayed (with version, sheet and language currently set). Mainly used for showing "unused elements" in sidebar.
	var $elementBlacklist=array();					// Used in renderFrameWork (list of CEs causing errors)

	/**
	 * Intialize the CE render engine
	 *
	 * @param	object		The calling (parent) object
	 * @return	void
	 */
	function init(&$calling_obj)	{
		$this->calling_obj = &$calling_obj;
		$this->topPagePid = $this->calling_obj->P['pid'];
		$this->doc = &$this->calling_obj->doc;
		$this->backPath = &$this->calling_obj->backPath;
		$this->MOD_SETTINGS = &$this->calling_obj->MOD_SETTINGS;
		$this->P = &$this->calling_obj->P;
		$this->id = &$this->P['pid'];
		$this->flexData = &$this->calling_obj->flexData;
		$this->flexDS = &$this->calling_obj->flexDS;
		$this->clipObj = &$this->calling_obj->clipObj;
		$this->funcs = t3lib_div::makeInstance('tx_kbconttable_funcs');
		$this->funcs->init($this);
		$this->tableData = $this->calling_obj->tableData;
	}

	/**
	 * Renders the content elements area. With copy & paste and new links and all other stuff.
	 *
	 * @param	string		Elements in this cell.
	 * @param	integer		Row index
	 * @param	integer		Column index
	 * @param	integer		Row
	 * @param	integer		Column
	 * @param	string		Cell title
	 * @return	string		HTML
	 */
	function main($elements, $row_idx, $col_idx, $row, $col, $header)	{
		global $LANG;
		$this->elements = t3lib_div::trimExplode(',', $elements, 1);
		$this->row = $row;
		$this->col = $col;
		$this->elements_ar = Array();
		foreach ($this->elements as $element)	{
			if (!t3lib_div::testInt($element))	{
				$parts = explode('_', $element);
				$element = array_pop($parts);
				$table = implode('_', $parts);
				if ($table != 'tt_content')	{
					return 'Content Element from invalid table "'.$table.'". Just tt_content allowed !';
				}
			}
			$el = t3lib_BEfunc::getRecord('tt_content', $element);
			if ($el)	{
				$page = t3lib_BEfunc::getRecord('pages', $el['pid']);
				if ($page)	{
					$this->elements_ar[$el['uid']]['data'] = $el;
					$this->elements_ar[$el['uid']]['page'] = $page;
				} else	{
				return 'Page "'.$el['pid'].'" for Content Element "'.$element.'" not found !';
				}
			} else	{
				return 'Content Element "'.$element.'" not found !';
			}
		}

			// Setting whether an element is on the clipboard or not
		$elFromTable = array_merge($this->clipObj->elFromTable('tt_content'), $this->clipObj->elFromTable('XML'));
		$clipboardElInPath = (!trim(count($elFromTable)?'1':'') ? 1 : 0);

		$this->TopPage = t3lib_BEfunc::getRecord('pages', $this->P['pid']);
		if (!$this->TopPage)	{
			return 'Page for Element not found';
		}

		$cells=array();
		$headerCells=array();
		$metaInfoAreaArr = array();

		$dsInfo = $this->TV_flexGen($row, $col, $header);


		$elementBackgroundStyle = '';
		$elementPageTitlebarColor = isset ($this->flexData['data']['sDEF']['tableDesign']['titleBarColor']) ?  $this->flexData['data']['sDEF']['tableDesign']['titleBarColor'] : (!$this->tableData[$row]['visible']||$this->tableData[$row][$col]['hidden']||!$this->tableData[$row][$col]['visible']?'#7E8389':$this->doc->bgColor2);
		$elementPageTitlebarStyle = 'background-color: '.($dsInfo['el']['table']=='pages' ? $elementPageTitlebarColor : ($isLocal ? (!$this->tableData[$row]['visible']||$this->tableData[$row][$col]['hidden']||!$this->tableData[$row][$col]['visible']?'#84918B':$this->doc->bgColor5) : (!$this->tableData[$row]['visible']||$this->tableData[$row][$col]['hidden']||!$this->tableData[$row][$col]['visible']?'#C6BC90':$this->doc->bgColor6))) .';';
		$elementCETitlebarStyle = 'background-color: '.$this->doc->bgColor4.';';
		$headerCellStyle = 'background-color: '.$this->doc->bgColor4.';';
		$cellStyle = 'border: 1px dashed #666666;';

		if (is_array($dsInfo['sub']['sDEF']))	{
			foreach($dsInfo['sub']['sDEF'] as $fieldID => $fieldContent)	{
				$counter=0;

				// Only show fields and values of a flexible content element, if either the currently selected language is the DEF language, or the langDisable flag of the FCE's data structure is not set

					// "New" and "Paste" icon:
				$elList = '';
				if (!$this->tableData[$row][$col]['lock_content'])	{
					$elList .= $this->linkNew('<img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/new_el.gif','').' style="text-align: center; vertical-align: middle;" vspace="5" border="0" title="'.$LANG->getLL ('createnewrecord').'" alt="" />', 'tt_content:'.$this->P['uid'].':s_row_'.$row.':lDEF:column_'.$row.'_'.$col.'_elements:vDEF:'.$counter);
				}
				if (!$clipboardElInPath&&!$this->tableData[$row][$col]['lock_content'])	{
					$GLOBALS['TCA']['XML'] = 1;	// Make the clipboard class happy
					$elList .= '<a href="'.htmlspecialchars($this->clipObj->pasteUrl('XML', $this->P['table'].':'.$this->P['uid'].':s_row_'.$row.':lDEF:column_'.$row.'_'.$col.'_elements:vDEF:'.$counter, 1, isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['colPos'])?intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['colPos']):10, Array('kbconttable' => ''))).'" onclick="'.htmlspecialchars('return '.$this->clipObj->confirmMsg('tt_content',$this->elements_ar[$counter]['data'],'after',$elFromTable)).'"><img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/clip_pasteafter.gif','width="12" height="12"').' title="'.$LANG->getLL('clip_pasteAfter',1).'" alt="" / style="text-align: center; vertical-align: middle;"></a>';
					unset($GLOBALS['TCA']['XML']);	// Make the world happy
				}


					// Render the list of elements :
				if (is_array($fieldContent['el_list']))	{
					foreach($fieldContent['el_list'] as $counter => $k)	{
						$v = $fieldContent['el'][$k];
						$this->containedElements[$this->containedElementsPointer]++;
						$elList.=$this->renderFrameWork($v,$clipboardElInPath,$referenceInPath, $counter);

							// "New" and "Paste" icon:
						if (!$this->tableData[$row][$col]['lock_content'])	{
							$elList .= $this->linkNew('<img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/new_el.gif','').' style="text-align: center; vertical-align: middle;" vspace="5" border="0" title="'.$LANG->getLL ('createnewrecord').'" alt="" />', 'tt_content:'.$this->P['uid'].':s_row_'.$row.':lDEF:column_'.$row.'_'.$col.'_elements:vDEF:'.$counter);
						}
						if (!$clipboardElInPath&&!$this->tableData[$row][$col]['lock_content'])	{
							$GLOBALS['TCA']['XML'] = 1;	// Make the clipboard class happy
							$elList .= '<a href="'.htmlspecialchars($this->clipObj->pasteUrl('XML', $this->P['table'].':'.$this->P['uid'].':s_row_'.$row.':lDEF:column_'.$row.'_'.$col.'_elements:vDEF:'.$counter, 1, isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['colPos'])?intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['colPos']):10, Array('kbconttable' => ''))).'" onclick="'.htmlspecialchars('return '.$this->clipObj->confirmMsg('tt_content',$this->elements_ar[$counter]['data'],'after',$elFromTable)).'"><img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/clip_pasteafter.gif','width="12" height="12"').' title="'.$LANG->getLL('clip_pasteAfter',1).'" alt="" / style="text-align: center; vertical-align: middle;"></a>';
							unset($GLOBALS['TCA']['XML']);	// Make the world happy
						}
					}
				}

					// Add cell content to registers:
				$headerCells[]='<td valign="top" width="'.round(100/count($dsInfo['sub']['sDEF'])).'%" style="'.$headerCellStyle.' padding-top:0; padding-bottom:0;">'.$fieldContent['meta']['title'].'</td>';
				$cells[]='<td valign="top" width="'.round(100/count($dsInfo['sub']['sDEF'])).'%" style="'.$cellStyle.' padding: 2px 2px 2px 2px;">'.$errorLineCell.$elList.'</td>';
			}
		}


		if (count ($headerCells) || count ($cells))	{
			$content .= '
				<table border="0" cellpadding="2" cellspacing="2" width="100%">
					<tr>'.(count($headerCells) ? implode('',$headerCells) : '<td>&nbsp;</td>').'</tr>
					<tr>'.(count($cells) ? implode('',$cells) : '<td>&nbsp;</td>').'</tr>
				</table>
			';
		}

		$finalContent =
			($errorLineBefore ? '<br />'.$errorLineBefore : ''). '
		<table border="0" cellpadding="0" cellspacing="0" style="'.$elementBackgroundStyle.'" width="100%">
			<tr style="'.$elementPageTitlebarStyle.';">
				<td nowrap="nowrap">'.$ruleIcon.$langIcon.$recordIcon.$viewPageIcon.'</td><td width="95%" '.$titleBarTDParams.'>'.($isLocal?'':'<em>').htmlspecialchars($dsInfo['el']['title']).($isLocal?'':'</em>'). '</td>
				<td nowrap="nowrap" align="right" valign="top">'.
					$linkCustom.
					$linkMakeLocal.
					$linkCopy.
					$linkCut.
					$linkRef.
					$linkUnlink.
					($isLocal ? $this->linkEdit('<img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/edit2.gif','').' title="'.$LANG->getLL ('editrecord').'" border="0" alt="" />',$dsInfo['el']['table'],$dsInfo['el']['id']) : '').
				'</td>
			</tr>
			<tr>
				<td colspan="3">'.
					$contentWrapPre.
					$content.
					($errorLineWithin ? '<br />'.$errorLineWithin : '').
					$llTable.
					$contentWrapPost.
				'</td>
			</tr>
		</table>
		'.$errorLineAfter.'
		';

		return $finalContent;
	}

	/**
	 * Generate the necessary XML for the field
	 *
	 * @param	integer		Row
	 * @param	integer		Column
	 * @param	string		Field title
	 * @return	array		Flex DS
	 */
	function TV_flexGen($row, $col, $header)	{

		$el = Array();
		$el_list = Array();
		$cnt = 0;
		$db_list = t3lib_div::makeInstance('tx_kbconttable_layout');
		$db_list->previewTextLen = isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['previewTextLen'])?intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['previewTextLen']):100;
		$db_list->backPath = $this->doc->backPath;
		foreach ($this->elements_ar as $element => $el_ar)	{
			$el_row = $el_ar['data'];
			$el_page = $el_ar['page'];
			if ($el_row)	{
				$cnt++;
				$isRTE = $RTE && $db_list->isRTEforField('tt_content',$el_row,'bodytext');
				$preview = $db_list->tt_content_drawItem($el_row,$isRTE);
				$new = Array(
					'tt_content:'.$el_row['uid'] => Array(
						'el' => Array(
							'table' => 'tt_content',
							'id' => $el_row['uid'],
							'pid' => $el_row['pid'],
							'title' => t3lib_div::fixed_lgd(t3lib_BEfunc::getRecordTitle('tt_content', $el_row),50),
							'icon' => t3lib_iconWorks::getIcon('tt_content', $el_row),
							'sys_language_uid' => $el_row['sys_language_uid'],
							'l18n_parent' => $el_row['l18n_parent'],
							'CType' => $el_row['CType'],
							'index' => $cnt,
							'previewContent' => Array(
								$preview,
							)
						),
					),
				);
				$el = array_merge($el, $new);
				$el_list[$cnt] = 'tt_content:'.$el_row['uid'];
			}
		}
		$dsInfo = Array(
			'el' => Array(
				'table' => 'pages',
				'id' => $this->TopPage['uid'],
				'pid' => $this->TopPage['pid'],
				'title' => t3lib_div::fixed_lgd(t3lib_BEfunc::getRecordTitle($table, $TopPage),50),
				'icon' => t3lib_iconWorks::getIcon('page', $TopPage),
				'sys_language_uid' => $page['sys_language_uid'],
				'l18n_parent' => $page['l18n_parent'],
				'CType' => $page['CType'],
			),
			'sub' => Array(
				'sDEF' => Array(
					'column_'.$row.'_'.$col.'_elements' => Array(
						'el' => $el,
						'meta' => Array(
							'title' => $header,
							'langDisable' => 1,
							'langChildren' => 1,
						),
						'el_list' => $el_list,
					),
				),
			),
			'meta' => Array(
				'sDEF' => Array(
					'title' => '',
					'description' => '',
					'short' => '',
				),
			),
		);
		return $dsInfo;
	}


	/**
	 * Renders the display framework.
	 * Calls itself recursively
	 *
	 * @param	array		$dsInfo: DataStructure info array (the whole tree)
	 * @param	string		$parentPos: Pointer to parent element: table:id:sheet:structure language:fieldname:value language:counter (position in list)
	 * @param	boolean		$clipboardElInPath: Tells whether any element registered on the clipboard is found in the current "path" of the recursion. If true, it normally means that no paste-in symbols are shown since elements are not allowed to be pasted/referenced to a position within themselves (would result in recursion).
	 * @param	boolean		$referenceInPath: Is set to the number of references there has been in previous recursions of this function
	 * @param	string		$sheet: The sheet key of the sheet which should be rendered
	 * @return	string		HTML
	 */
	function renderFrameWork($dsInfo, $clipboardElInPath, $referenceInPath, $counter = 0)	{
		global $LANG, $TYPO3_CONF_VARS, $TCA;

		$sheet = 'sDEF';
		if (!is_array($this->currentDataStructureArr[$dsInfo['el']['table']]))		{
			$this->currentDataStructureArr[$dsInfo['el']['table']] = array();
		}

		$lKey = 'lDEF';
		$vKey = 'vDEF';

			// The $isLocal flag is used to denote whether an element belongs to the current page or not. If NOT the $isLocal flag means (for instance) that the title bar will be colored differently to show users that this is a foreign element not from this page.
		$isLocal = $dsInfo['el']['table']=='pages' || $dsInfo['el']['pid']==$this->topPagePid;	// Pages have the local style
		if (!$isLocal) { $referenceInPath++; }

			// Set additional information to the title-attribute of the element icon:
		$extPath = '';
		if (!$isLocal)	{
			$extPath = ' - '.$LANG->getLL('path').': '.t3lib_BEfunc::getRecordPath($dsInfo['el']['pid'],$this->calling_obj->perms_clause,30);
		}

			// Evaluating the rules and set colors to warning scheme if a rule does not apply
		$elementBackgroundStyle = '';
		$elementPageTitlebarColor = '#9BA1A8';

		$elementPageTitlebarStyle = 'background-color: '.($dsInfo['el']['table']=='pages' ? $elementPageTitlebarColor : ($isLocal ? (!$this->tableData[$row]['visible']||$this->tableData[$this->row][$this->col]['hidden']||!$this->tableData[$this->row][$this->col]['visible']?'#84918B':$this->doc->bgColor5) : (!$this->tableData[$this->row]['visible']||$this->tableData[$this->row][$this->col]['hidden']||!$this->tableData[$this->row][$this->col]['visible']?'#C6BC90':$this->doc->bgColor6))) .';';
		$headerCellStyle = 'background-color: '.$this->calling_obj->doc->bgColor4.';';
		$cellStyle = 'border: 1px dashed #666666;';

		$errorLineBefore = $errorLineWihtin = $errorLineAfter = $errorLineCell = '';

			// Compile preview content for the current element:
		$content = is_array($dsInfo['el']['previewContent']) ? implode('<br />', $dsInfo['el']['previewContent']) : '';

			// Put together the records icon including content sensitive menu link wrapped around it:
		$recordIcon = '<img'.t3lib_iconWorks::skinImg($this->calling_obj->doc->backPath,$dsInfo['el']['icon'],'').' style="text-align: center; vertical-align: middle;" width="18" height="16" border="0" title="'.htmlspecialchars('['.$dsInfo['el']['table'].':'.$dsInfo['el']['id'].']'.$extPath).'" alt="" />';
		$recordIcon = $this->calling_obj->doc->wrapClickMenuOnIcon($recordIcon,$dsInfo['el']['table'],$dsInfo['el']['id'],1,'&callingScriptId='.rawurlencode($this->calling_obj->doc->scriptID), 'new,copy,cut,pasteinto,pasteafter,delete');

#		$realDelete = $isLocal;	// content elements that are NOT references from other pages will be deleted when unlinked
		$realDelete = FALSE;	// Eventually it seems that deleting content elements is not a good long term idea. Therefore, regardless of situation, we ALWAYS unlink - unused Content Elements can be cleaned up by some other tool some other day.

		$linkCustom = $linkCopy = $linkCut = $linkRef = $linkUnlink = $linkMakeLocal = $titleBarTDParams = $contentWrapPre = $contentWrapPost = '';

		if ($dsInfo['el']['table']!='tt_content')	{

			$viewPageIcon = '<a href="#" onclick="'.htmlspecialchars(t3lib_BEfunc::viewOnClick($dsInfo['el']['table']=='pages'?$dsInfo['el']['id']:$dsInfo['el']['pid'],$this->calling_obj->doc->backPath,t3lib_BEfunc::BEgetRootLine($dsInfo['el']['id']),'','','')).'">'.
				'<img'.t3lib_iconWorks::skinImg($this->calling_obj->doc->backPath,'gfx/zoom.gif','width="12" height="12"').' title="'.$LANG->sL('LLL:EXT:lang/locallang_core.php:labels.showPage',1).'" hspace="3" alt="" style="text-align: center; vertical-align: middle;" />'.
				'</a>';
		} else {
			$linkMakeLocal = (!$isLocal && $referenceInPath<=1) ? $this->linkMakeLocal('<img'.t3lib_iconWorks::skinImg($this->doc->backPath,t3lib_extMgm::extRelPath('kb_conttable').'res/makelocalcopy.gif','').' title="'.$LANG->sL('LLL:EXT:kb_conttable/locallang_db.xml:makeLocal').'" border="0" alt="" />', $this->P['table'].':'.$this->P['uid'].':s_row_'.$this->row.':lDEF:column_'.$this->row.'_'.$this->col.'_elements:vDEF:'.$counter.'/'.$dsInfo['el']['table'].':'.$dsInfo['el']['id'].'/'.$isLocal.'/'.$this->id) : '';
			$isSel = (string)$this->clipObj->isSelected($dsInfo['el']['table'],$dsInfo['el']['id']);
			if (!$isSel) {
				$isSel = (string)$this->clipObj->isSelected('XML', $this->P['table'].':'.$this->P['uid'].':s_row_'.$this->row.':lDEF:column_'.$this->row.'_'.$this->col.'_elements:vDEF:'.$counter.'/'.$dsInfo['el']['table'].':'.$dsInfo['el']['id'].'/'.($isLocal?'1':'').'/'.$this->id);
			}
			$linkCopy = $linkRef = $linkCut = $linkUnlink = '';
			$linkCopy='<a href="#" onclick="'.htmlspecialchars('return jumpSelf(\''.$this->clipObj->selUrlDB('XML', $this->P['table'].':'.$this->P['uid'].':s_row_'.$this->row.':lDEF:column_'.$this->row.'_'.$this->col.'_elements:vDEF:'.$counter.'/'.$dsInfo['el']['table'].':'.$dsInfo['el']['id'].'/'.$isLocal.'/'.$this->id,1,($isSel=='copy'),array('returnUrl'=>'', 'kbconttable' => '')).'\');').'"><img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/clip_copy'.($isSel=='copy'?'_h':'').'.gif','width="12" height="12"').' title="'.$LANG->sL('LLL:EXT:lang/locallang_core.php:cm.copy',1).'" alt="" /></a>';
			$linkRef = '<a href="#" onclick="'.htmlspecialchars('return jumpSelf(\''.$this->clipObj->selUrlDB('XML', $this->P['table'].':'.$this->P['uid'].':s_row_'.$this->row.':lDEF:column_'.$this->row.'_'.$this->col.'_elements:vDEF:'.$counter.'/'.$dsInfo['el']['table'].':'.$dsInfo['el']['id'].'/'.$isLocal.'/'.$this->id ,2,($isSel=='ref'),array('returnUrl'=>'', 'kbconttable' => '')).'\');').'"><img'.t3lib_iconWorks::skinImg($this->doc->backPath,t3lib_extMgm::extRelPath('kb_conttable').'res/clip_ref'.($isSel=='ref'?'_h':'').'.gif','').' title="'.$LANG->sL('LLL:EXT:lang/locallang_core.php:cm.cut',1).'" alt="" /></a>';
			if (!$this->tableData[$this->row][$this->col]['lock_content']) {
				$linkCut = '<a href="#" onclick="'.htmlspecialchars('return jumpSelf(\''.$this->clipObj->selUrlDB('XML', $this->P['table'].':'.$this->P['uid'].':s_row_'.$this->row.':lDEF:column_'.$this->row.'_'.$this->col.'_elements:vDEF:'.$counter.'/'.$dsInfo['el']['table'].':'.$dsInfo['el']['id'].'/'.$isLocal.'/'.$this->id ,0,($isSel=='cut'),array('returnUrl'=>'', 'kbconttable' => '')).'\');').'"><img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/clip_cut'.($isSel=='cut'?'_h':'').'.gif','width="12" height="12"').' title="'.$LANG->sL('LLL:EXT:lang/locallang_core.php:cm.cut',1).'" alt="" /></a>';
   			$linkUnlink = $this->linkUnlink('<img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/garbage.gif','').' title="'.$LANG->getLL($realDelete ? 'deleteRecord' : 'unlinkRecord').'" border="0" alt="" />', $this->P['table'].':'.$this->P['uid'].':s_row_'.$this->row.':lDEF:column_'.$this->row.'_'.$this->col.'_elements:vDEF:'.intval($counter).'/'.$dsInfo['el']['table'].':'.$dsInfo['el']['id'].'/'.$isLocal.'/'.$this->id, $realDelete);
			}

		}

		$finalContent =
			($errorLineBefore ? '<br />'.$errorLineBefore : ''). '
		<table border="0" cellpadding="0" cellspacing="0" style="border: 1px solid black; margin-bottom:2px; '.$elementBackgroundStyle.'" width="100%">
			<tr style="'.$elementPageTitlebarStyle.';">
				<td nowrap="nowrap">'.$ruleIcon.$langIcon.$recordIcon.$viewPageIcon.'</td><td width="95%" '.$titleBarTDParams.'>'.($isLocal?'':'<em>').htmlspecialchars($dsInfo['el']['title']).($isLocal?'':'</em>'). '</td>
				<td nowrap="nowrap" align="right" valign="top">'.
					$linkCustom.
					$linkMakeLocal.
					'&nbsp;&nbsp;'.
					$linkCopy.
					$linkCut.
					$linkRef.
					$linkUnlink.
					(($isLocal && !$this->tableData[$this->row][$this->col]['lock_content']) ? $this->linkEdit('<img'.t3lib_iconWorks::skinImg($this->calling_obj->doc->backPath,'gfx/edit2.gif','').' title="'.$LANG->getLL ('editrecord').'" border="0" alt="" />',$dsInfo['el']['table'],$dsInfo['el']['id']) : '').
				'</td>
			</tr>
			<tr>
				<td colspan="3">'.
					$contentWrapPre.
					$content.
					($errorLineWithin ? '<br />'.$errorLineWithin : '').
					$llTable.
					$contentWrapPost.
				'</td>
			</tr>
		</table>
		'.$errorLineAfter.'
		';

		return $finalContent;
	}




	/**
	 * Returns an HTML link for making a reference content element local to the page (copying it).
	 *
	 * @param	string		$str: The label
	 * @param	string		$unlinkRecord: The parameters for unlinking the record. Example: pages:78:sDEF:lDEF:field_contentarea:vDEF:0
	 * @return	string		HTML anchor tag containing the label and the unlink-link
	 */
	function linkMakeLocal($str, $makeLocalRecord)	{
		global $LANG;

		return '<a href="'.t3lib_div::linkThisScript(Array('CB' => '', 'SET' => '', 'P' => $this->calling_obj->P, 'kbconttable' => '', 'kbconttable[funcs][makeLocalRecord]' => $makeLocalRecord)).'" onclick="'.htmlspecialchars('return confirm('.$LANG->JScharCode($LANG->sL('LLL:EXT:kb_conttable/locallang_db.xml:makeLocalMsg')).');').'">'.$str.'</a>';
	}

	/**
	 * Returns an HTML link for unlinking a content element. Unlinking means that the record still exists but
	 * is not connected to any other content element or page.
	 *
	 * @param	string		$str: The label
	 * @param	string		$unlinkRecord: The parameters for unlinking the record. Example: pages:78:sDEF:lDEF:field_contentarea:vDEF:0
	 * @param	bool		Wheter a record shall be really deleted
	 * @return	string		HTML anchor tag containing the label and the unlink-link
	 */
	function linkUnlink($str, $unlinkRecord, $realDelete=FALSE)	{
		global $LANG;

		if ($realDelete)	{
			return '<a href="'.t3lib_div::linkThisScript(Array('CB' => '', 'SET' => '', 'kbconttable' => '', 'P' => $this->P, 'kbconttable[funcs][deleteRecord]' => $unlinkRecord)).'" onclick="'.htmlspecialchars('return confirm('.$LANG->JScharCode($LANG->getLL('deleteRecordMsg')).');').'">'.$str.'</a>';
		} else {
			return '<a href="'.t3lib_div::linkThisScript(Array('CB' => '', 'SET' => '', 'kbconttable' => '', 'P' => $this->P, 'kbconttable[funcs][unlinkRecord]' => $unlinkRecord)).'">'.$str.'</a>';
		}
	}

	/**
	 * Returns an HTML link for editing
	 *
	 * @param	string		$str: The label (or image)
	 * @param	string		$table: The table, fx. 'tt_content'
	 * @param	integer		$uid: The uid of the element to be edited
	 * @return	string		HTML anchor tag containing the label and the correct link
	 */
	function linkEdit($str, $table, $uid)	{
		$onClick = t3lib_BEfunc::editOnClick('&edit['.$table.']['.$uid.']=edit',$this->calling_obj->doc->backPath);
		return '<a style="text-decoration: none;" href="#" onclick="'.htmlspecialchars($onClick).'">'.$str.'</a>';
	}


	/**
	 * Returns an HTML link for creating a new record
	 *
	 * @param	string		$str: The label (or image)
	 * @param	string		$parentRecord: The parameters for creating the new record. Example: pages:78:sDEF:lDEF:field_contentarea:vDEF:0
	 * @return	string		HTML anchor tag containing the label and the correct link
	 */
	function linkNew($str, $parentRecord)	{
		return '<a href="'.htmlspecialchars('db_new_content_el.php?'.$this->funcs->linkParams().'&parentRecord='.rawurlencode($parentRecord)).'">'.$str.'</a>';
	}



}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_berenderCE.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_berenderCE.php']);
}

?>
