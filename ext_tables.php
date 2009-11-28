<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$tempColumns = Array (
	'tx_kbconttable_flex_ds' => Array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:kb_conttable/locallang_db.php:tt_content.tx_kbconttable_flex_ds',		
		'config' => Array (
			'type' => 'text',
			'cols' => '30',	
			'rows' => '5',	
			'wizards' => Array(
				'_PADDING' => 2,
				'example' => Array(
					'title' => 'Example Wizard:',
					'type' => 'script',
					'notNewRecords' => 1,
					'icon' => t3lib_extMgm::extRelPath('kb_conttable').'tt_content_tx_kbconttable_flex_ds/wizard_icon.gif',
					'script' => t3lib_extMgm::extRelPath('kb_conttable').'tt_content_tx_kbconttable_flex_ds/index.php',
					'hideParent' => 1,
				),
			),
		)
	),
	'tx_kbconttable_flex' => Array (
		'exclude' => 1,
		'label' => 'LLL:EXT:kb_conttable/locallang_db.php:tt_content.tx_kbconttable_flex',
		'config' => Array (
			'type' => 'flex',
			'ds_pointerField' => 'uid',
			'ds_tableField' => 'tt_content:tx_kbconttable_flex_ds',
		)
	),
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);

$TCA['tx_kbconttable_tmpl'] = Array (
	'ctrl' => Array (
		'title' => 'LLL:EXT:kb_conttable/locallang_db.php:tx_kbconttable_tmpl',		
		'label' => 'name',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY name',	
		'delete' => 'deleted',	
		'enablecolumns' => Array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_kbconttable_tmpl.gif',
	),
	'feInterface' => Array (
		'fe_admin_fieldList' => 'hidden, name, tsconfig_name, allowed_users, allowed_groups, content_mode, flex, flex_ds',
	)
);

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types'][$_EXTKEY.'_pi1']['showitem']='CType;;4;button;1-1-1, header;;3;;2-2-2, tx_kbconttable_flex_ds;;;;1-1-1, tx_kbconttable_flex';

if (TYPO3_MODE=='BE')	{
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_kbconttable_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'class.tx_kbconttable_wizicon.php';
}

$TCA['tt_content']['columns']['colPos']['config']['items'][] = array('LLL:EXT:kb_conttable/locallang_db.php:content_column', $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['colPos']);


t3lib_extMgm::addPlugin(Array('LLL:EXT:kb_conttable/locallang_db.php:tt_content.CType_pi1', $_EXTKEY.'_pi1'),'CType');

?>
