<?php

########################################################################
# Extension Manager/Repository config file for ext "kb_conttable".
#
# Auto generated 05-05-2010 14:49
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'KB Content Table',
	'description' => 'Contentelement for enhanced table editing and content element integration',
	'category' => 'fe',
	'shy' => 0,
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'tt_content_tx_kbconttable_flex_ds',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => 'uploads/tx_kbconttable/rte/',
	'modify_tables' => 'tt_content',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Bernhard Kraft',
	'author_email' => 'kraftb@seicht.co.at',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.4.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.7.0-0.0.0',
			'php' => '5.0.0-0.0.0',
			'cms' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => '',
	'suggests' => array(
	),
);

?>
