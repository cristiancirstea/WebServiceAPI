<?php
    //include_once dirname(__FILE__).'/../library/Utils.php';
    $_COMMON_CSS_FOLDER = './service_methods/library/css/';
    $_COMMON_JS_FOLDER = './service_methods/library/js/';
    //aparent nu ma lasa sa fac automat referire la directorul curent
    ////$_COMMON_CSS_FOLDER = dirname(__FILE__).'/../library/css/';
    //$_COMMON_JS_FOLDER = dirname(__FILE__).'/../library/js/';
	$_COMMON_CSS_FILES = array(
		'bootstrap.min.css',
                'bootstrap-responsive.min.css',
                'bootstrap-datetimepicker.min.css',
                'datepicker.css',
                //'font-awesome.min.css',
                'style.css'
	);
    $_COMMON_JS_FILES = array(
	'jQuery.min.js',
	'json2.js',
        "md5.js",
        "bootpag.js",
        "bootstrap.min.js",
        "bootstrap-datetimepicker.min.js",
        "bootstrap-datepicker.js",
        "locales/bootstrap-datepicker.ro.js",
        "detectmobilebrowser.js",
        //'jquery.event.drag.live-2.2.js',
        //'jquery.event.drag-2.2.js',
        //'jquery.event.drop.live-2.2.js',
        //'jquery.event.drop-2.2.js',
	//	'common.js',
		'utils.js',
                'script.js'
        );
    $arAdditionalLink[] = array("icon",$_COMMON_CSS_FOLDER."ws-icon1.png","image");
    
    foreach ($_COMMON_JS_FILES as $index => $filename) {
       $arAdditionalScript[] = $_COMMON_JS_FOLDER.$filename; 
    }
    foreach ($_COMMON_CSS_FILES as $index => $filename) {
       $arAdditionalLink[] = array("stylesheet",$_COMMON_CSS_FOLDER.$filename,'text/css'); 
    }
   // $arAdditionalScript[] = $_COMMON_JS_FOLDER."script.js";
    
?>