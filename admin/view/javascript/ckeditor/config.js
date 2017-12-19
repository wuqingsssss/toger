/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'zh-cn';
	// config.uiColor = '#AADC6E';
	
	
	//是否可以关闭工具栏
	config.toolbarCanCollapse = false;

	//改变大小的最大高度 
	config.resize_maxHeight= 500; 
 
    //改变大小的最大宽度 
	config.resize_maxWidth =  1200;
 
    //改变大小的最小高度 
	config.resize_minHeight =  500;
 
    //改变大小的最小宽度 
	config.resize_minWidth =  500;

	//设置HTML文档类型
	config.docType =  '<!DOCTYPE html>';

	//是否强制复制来的内容去除格式 plugins/pastetext/plugin.js 
	config.forcePasteAsPlainText =  false;

	//是否使用完整的html编辑模式 如使用，其源码将包含：<html><body></body></html>等标签 
	config.fullPage =  false;

 	//是否使用<h1><h2>等标签修饰或者代替从word文档中粘贴过来的内容 plugins/pastefromword/plugin.js 
	config.pasteFromWordKeepsStructure =  false;

	config.startupOutlineBlocks =  false;

	config.width =  800;
	config.height = 300;
	
	config.filebrowserBrowseUrl = 'index.php?route=common/filemanager';
	config.filebrowserImageBrowseUrl = 'index.php?route=common/filemanager';
	config.filebrowserFlashBrowseUrl = 'index.php?route=common/filemanager';
	config.filebrowserUploadUrl = 'index.php?route=common/filemanager';
	config.filebrowserImageUploadUrl = 'index.php?route=common/filemanager';
	config.filebrowserFlashUploadUrl = 'index.php?route=common/filemanager';		
	config.filebrowserWindowWidth = '800';
	config.filebrowserWindowHeight = '500';
	
	config.font_names='宋体/宋体;黑体/黑体;仿宋/仿宋_GB2312;楷体/楷体_GB2312;隶书/隶书;幼圆/幼圆;微软雅黑/微软雅黑;'+ config.font_names;
	
	config.resize_enabled = false;
	
	config.htmlEncodeOutput = false;
	config.entities = false;
	
	config.toolbar = 'Custom';

	config.toolbar_Min = [
	     ['Bold'],
	     ['JustifyLeft']
	];
	
	config.toolbar_Custom = [
	    ['Maximize','Source',],
		['Font','FontSize','TextColor','BGColor','Bold','Underline','Strike','JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
		['Image','Flash','Table','HorizontalRule','SpecialChar','PageBreak','Link','Unlink']

	];
	
	config.toolbar_Full = [
		['Source','-','Save','NewPage','Preview','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks','-','About']
	];
};