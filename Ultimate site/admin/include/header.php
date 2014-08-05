<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
	<title><?=$title?> - Администрирование сайта ultimate.com.ua</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<link href="/css/index-wide.css" rel="stylesheet">
	<link href="css/admin.css" rel="stylesheet">
	<link rel="icon" href="/admin/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="/admin/favicon.ico" type="image/x-icon">
		<!-- Load jQuery -->
		<script language="JavaScript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
		<!--<script type="text/javascript" src="/js/jquery.js"></script>-->

		<!-- Load TinyMCE -->
		<script type="text/javascript" src="/admin/tinymce/jquery.tinymce.js"></script>
		<script type="text/javascript">
			$().ready(function() {
				$('textarea.tinymce').tinymce({
					// Location of TinyMCE script
					script_url : '/admin/tinymce/tiny_mce.js',

					// General options
					theme : "advanced",
					plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
					convert_urls : false,

					// Theme options
					theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
					theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
					theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
					theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : true,

					// Example content CSS (should be your site CSS)
					content_css : "/css/index-wide-0.css",

					// Drop lists for link/image/media/template dialogs
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js",

					// Replace values for the template plugin
					template_replace_values : {
						username : "Some User",
						staffid : "991234"
					}
				});
			});
		</script>
		<!-- /TinyMCE -->
	<link href="/css/datePicker.css" rel="stylesheet" />
	<script type="text/javascript" src="/js/jquery.datePicker.js"></script>
	<script type="text/javascript" src="/js/date.js"></script>
	<script type="text/javascript" src="/js/date_ru_win1251.js"></script>
	<script type="text/javascript">
		$(function()
		{
			$('.date-pick').datePicker();
		});
	</script>
</head>

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">

<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center">
<tr><td height="50 px" colspan="2" class="light">
<!-- Top -->
<?include "top.php"?>
<!-- End of Top -->
</td></tr>
<tr><th width="100%" height="2" colspan="2" ><h3><?=$title?></h3>
</th></tr>
<tr>
<td valign="top" class="light">
<!-- Left Menu -->
<?include "menu.php"?>
<!-- End of Left Menu -->
</td>
<td width="100%" valign="top">
<!-- Main part -->
