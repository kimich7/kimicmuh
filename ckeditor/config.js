/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.width = "95%";
	//工具列
	config.toolbar = 'TadToolbar';
	config.toolbar_TadToolbar =
	[
		['Source','-','Templates','-','Cut','Copy','Paste'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/', ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Format','FontSize','-','TextColor','BGColor']
	];
	
	//開啟圖片上傳功能
	config.filebrowserBrowseUrl = 'ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = 'ckfinder/ckfinder.html?Type=Images';
	config.filebrowserFlashBrowseUrl = 'ckfinder/ckfinder.html?Type=Flash';
	config.filebrowserUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'; //可上傳一般檔案
	config.filebrowserImageUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';//可上傳圖檔
	config.filebrowserFlashUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';//可上傳Flash檔案
	
	CKEDITOR.editorConfig = function( config ){ 
      config.filebrowserImageUploadUrl = './upload.php?type=img'; 
      config.filebrowserFlashUploadUrl = './upload.php?type=flash';
};
};
