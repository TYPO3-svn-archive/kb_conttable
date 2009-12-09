/***************************************************************
*  Copyright notice
*
*  (c) 2004-2009 Bernhard Kraft (kraftb@think-open.at)
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
 * Javascript functions required for the kb_conttable wizard
 *
 * $Id$
 *
 * @author	Bernhard Kraft <kraftb@think-open.at>
 */

var unsaved_global_content = 0;
var unsaved_content = 0;
var rteInitialized = 0;

function set_selectbox(boxname, value) {
	var cnt = 0;
	var idx = 0;
	var form = document.forms[0];
	for (child in document.forms[0][boxname].childNodes) {
		if (child=="length") continue;
		if (child=="item") continue;
		if (document.forms[0][boxname].childNodes[child].value==value) {
			form[boxname].childNodes[child]["selected"] = true;
			idx = cnt;
		} else {
			form[boxname].childNodes[child]["selected"] = false;
		}
		cnt++;
	}
	form[boxname]["selectedIndex"] = idx;
}



function close_celledit() {
	var cellprops = document.getElementById("DTM-cellprops-DIV");
	cellprops.style.display = "none";
	unsaved_content = 0;
	return true;
}

function debug(obj, vals) {
	cnt = 0;
	out = '';
	lines = 0;
	for (prop in obj) {
		out = out + prop;
		if (vals) out = out + ' : ' + obj[prop];
		if (!(++cnt%6)) {
			out = out + '\n';
			lines++;
		} else out = out + '       ';
		if (lines>10) {
			alert(out);
			out = '';
			lines = 0;
		}
	}
}


function kb_updateForm(row, col, form, field, value) {
	updateForm(form, field, value);
	form = eval('document.'+form);
	switch (field) {
		case 'id_val':
			lockfield = 'id';
		break;
		case 'style_val':
			lockfield = 'style';
		break;
		case 'cl_val':
			lockfield = 'class';
		break;
		default:
			lockfield = field;
		break;
	}
	if (parseInt(form['kbconttable[data]['+row+']['+col+'][lock_'+lockfield+']']['value'])>0) {
		form[field]['disabled'] = true;
	} else {
		form[field]['disabled'] = false;
	}
	return true;
}

var visible_row = 0;
var visible_column = 0;

function resetRTE() {
	alert("bla");
}

function show_celledit(row, col) {
	var prefix = "kbconttable[data]["+row+"]["+col+"]";
	var form = document.editform;
	kb_updateForm(row, col, "editform", "colspan", unescape(form[prefix+"[colspan]"].value))
	kb_updateForm(row, col, "editform", "rowspan", unescape(form[prefix+"[rowspan]"].value))
	kb_updateForm(row, col, "editform", "celltype", unescape(form[prefix+"[celltype]"].value))
	kb_updateForm(row, col, "editform", "cellwidth", unescape(form[prefix+"[cellwidth]"].value))
	kb_updateForm(row, col, "editform", "cellwidth_format", unescape(form[prefix+"[cellwidth_format]"].value))
	kb_updateForm(row, col, "editform", "cellheight", unescape(form[prefix+"[cellheight]"].value))
	kb_updateForm(row, col, "editform", "cellheight_format", unescape(form[prefix+"[cellheight_format]"].value))
	kb_updateForm(row, col, "editform", "align", unescape(form[prefix+"[align]"].value))
	kb_updateForm(row, col, "editform", "valign", unescape(form[prefix+"[valign]"].value))
	kb_updateForm(row, col, "editform", "wordwrap", unescape(form[prefix+"[wordwrap]"].value))
	kb_updateForm(row, col, "editform", "backgroundcolor", unescape(form[prefix+"[backgroundcolor]"].value))
	kb_updateForm(row, col, "editform", "color", unescape(form[prefix+"[color]"].value))
	kb_updateForm(row, col, "editform", "fontweight", unescape(form[prefix+"[fontweight]"].value))
	kb_updateForm(row, col, "editform", "fontfamily", unescape(form[prefix+"[fontfamily]"].value))
	kb_updateForm(row, col, "editform", "fontsize", unescape(form[prefix+"[fontsize]"].value))
	kb_updateForm(row, col, "editform", "fontsize_format", unescape(form[prefix+"[fontsize_format]"].value))
	kb_updateForm(row, col, "editform", "cl_val", unescape(form[prefix+"[class]"].value))
	kb_updateForm(row, col, "editform", "id_val", unescape(form[prefix+"[id]"].value))
	kb_updateForm(row, col, "editform", "additional", unescape(form[prefix+"[additional]"].value))
	kb_updateForm(row, col, "editform", "style_val", unescape(form[prefix+"[style]"].value))
	visible_row = row;
	visible_column = col;
	unsaved_content = 0;
	var cellprops = document.getElementById("DTM-cellprops-DIV");
	cellprops.style.display = "block";
	switch (rteMode)	{
		case 'rtehtmlarea':
			RTEarea["rte_content"].editor.getPluginInstance("EditorMode").setHTML(form[prefix+"[rte_content]"].value);
		break;
		case 'tinymce_rte':
			top.tinyMCE.activeEditor.setContent(form[prefix+"[rte_content]"].value);
		break;
		case 'default':
			TBE_RTE_WINDOWS['rte_content'].setHTML(form[prefix+"[rte_content]"].value, 0);
		break;
		case 'none':
			var ta = document.getElementById('rte_content');
			ta.value = form[prefix+"[rte_content]"].value;
		break;
		default:
		break;
	}
	return true;
}


function getSelectValue(field, def) {
	val = field.options[field.selectedIndex].value;
	if (val==undefined) val = def;
	if (typeof val==undefined) val = def;
	if (val==undefined) val = '';
	if (typeof val==undefined) val = '';
	return val;
}


function save_celledit() {
	row = visible_row;
	col = visible_column;
	var prefix = "kbconttable[data]["+row+"]["+col+"]";
	var form = document.forms[0];
	val = parseInt(form.colspan.value);
	if (isNaN(val)||(val < 1) || (val > 100)) {
		alert("Invalid Colspan value");
		return false;
	}
	form[prefix+"[colspan]"].value = val;
	val = parseInt(form.rowspan.value);
	if (isNaN(val)||(val < 1) || (val > 100)) {
		alert("Invalid Rowspan value");
		return false;
	}
	form[prefix+"[rowspan]"].value = val;
	form[prefix+"[celltype]"].value = getSelectValue(form.celltype, 'td');
	val = parseInt(form.cellwidth.value);
	if (isNaN(val)||(val < 0) || (val > 999999)) {
		alert("Invalid Cellwidth value");
		return false;
	}
	form[prefix+"[cellwidth]"].value = val;
	form[prefix+"[cellwidth_format]"].value = getSelectValue(form.cellwidth_format);
	val = parseInt(form.cellheight.value);
	if (isNaN(val)||(val < 0) || (val > 999999)) {
		alert("Invalid Cellheight value");
		return false;
	}
	form[prefix+"[cellheight]"].value = val;
	form[prefix+"[cellheight_format]"].value = getSelectValue(form.cellheight_format);
	form[prefix+"[align]"].value = getSelectValue(form.align);
	form[prefix+"[valign]"].value = getSelectValue(form.valign);
	form[prefix+"[wordwrap]"].value = getSelectValue(form.wordwrap);
	form[prefix+"[backgroundcolor]"].value = form.backgroundcolor.value;
	form[prefix+"[color]"].value = form.color.value;
	form[prefix+"[fontweight]"].value = getSelectValue(form.fontweight);
	form[prefix+"[fontfamily]"].value = form.fontfamily.value;
	val = parseInt(form.fontsize.value);
	if (isNaN(val)||(val < 0) || (val > 9999)) {
		alert("Invalid Fontsize value");
		return false;
	}
	form[prefix+"[fontsize]"].value = val;
	form[prefix+"[fontsize_format]"].value = getSelectValue(form.fontsize_format);
	form[prefix+"[class]"].value = form.cl_val.value;
	form[prefix+"[id]"].value = form.id_val.value;
	form[prefix+"[additional]"].value = form.additional.value;
	form[prefix+"[style]"].value = form.style_val.value;
	switch (rteMode)	{
		case 'rtehtmlarea':
			var localEditor = RTEarea["rte_content"].editor
			form[prefix+"[rte_content]"].value = localEditor.getPluginInstance("EditorMode").getHTML();
		break;
		case 'tinymce_rte':
			form[prefix+"[rte_content]"].value = top.tinyMCE.activeEditor.getContent();
		break;
		case 'default':
			form[prefix+"[rte_content]"].value = TBE_RTE_WINDOWS['rte_content'].getHTML();
		break;
		case 'none':
			var ta = document.getElementById('rte_content');
			form[prefix+"[rte_content]"].value = ta.value;
		break;
		default:
		break;
	}
	unsaved_content = 0;
	unsaved_global_content = 1;
	return true;
}

function prepare_form(close) {
	if (unsaved_global_content||unsaved_content) {
		if (save_celledit()) {
			document.editform["kbconttable[close]"].value = close;
			return true;
		} else {
			return false
		}
	} else {
		document.editform["kbconttable[close]"].value = close;
		return true;
	}
}


