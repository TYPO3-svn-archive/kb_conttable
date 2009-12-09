<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

$TCA["tx_kbconttable_tmpl"] = Array (
	"ctrl" => $TCA["tx_kbconttable_tmpl"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,name,tsconfig_name,allowed_users,allowed_groups,content_mode,flex,flex_ds"
	),
	"feInterface" => $TCA["tx_kbconttable_tmpl"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"name" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:kb_conttable/locallang_db.xml:tx_kbconttable_tmpl.name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
				"eval" => "trim",
			)
		),
		"tsconfig_name" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:kb_conttable/locallang_db.xml:tx_kbconttable_tmpl.tsconfig_name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
				"eval" => "trim,alphanum_x,nospace,unique,lower",
			)
		),
		"allowed_users" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:kb_conttable/locallang_db.xml:tx_kbconttable_tmpl.allowed_users",		
			"config" => Array (
				"type" => "group",	
				"internal_type" => "db",	
				"allowed" => "be_users",	
				"size" => 5,	
				"minitems" => 0,
				"maxitems" => 40,
			)
		),
		"allowed_groups" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:kb_conttable/locallang_db.xml:tx_kbconttable_tmpl.allowed_groups",		
			"config" => Array (
				"type" => "group",	
				"internal_type" => "db",	
				"allowed" => "be_groups",	
				"size" => 5,	
				"minitems" => 0,
				"maxitems" => 40,
			)
		),
		"content_mode" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:kb_conttable/locallang_db.xml:tx_kbconttable_tmpl.content_mode",		
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("LLL:EXT:kb_conttable/locallang_db.xml:tx_kbconttable_tmpl.content_mode.I.0", "0", t3lib_extMgm::extRelPath("kb_conttable")."res/selicon_tx_kbconttable_tmpl_content_mode_0.gif"),
					Array("LLL:EXT:kb_conttable/locallang_db.xml:tx_kbconttable_tmpl.content_mode.I.1", "1", t3lib_extMgm::extRelPath("kb_conttable")."res/selicon_tx_kbconttable_tmpl_content_mode_1.gif"),
					Array("LLL:EXT:kb_conttable/locallang_db.xml:tx_kbconttable_tmpl.content_mode.I.2", "2", t3lib_extMgm::extRelPath("kb_conttable")."res/selicon_tx_kbconttable_tmpl_content_mode_2.gif"),
				),
				"size" => 1,	
				"maxitems" => 1,
			)
		),
		"flex" => Array (		
			"config" => Array (
				"type" => "passthrough",
			)
		),
		"flex_ds" => Array (		
			"config" => Array (
				"type" => "passthrough",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, name, tsconfig_name, allowed_users, allowed_groups, content_mode, flex, flex_ds")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);
?>
