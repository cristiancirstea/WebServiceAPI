<?php
    include_once dirname(__FILE__).'/../library/Utils.php';
    $_COMMON_CSS_FOLDER = './library/css/';
    $_COMMON_JS_FOLDER = './library/js/';
    $_COMMON_JS_FILES = array("jQuery.min.js",
        "md5.js",
        "bootpag.js",
        "bootstrap.min.js",
        "bootstrap-datetimepicker.min.js",
        "bootstrap-datepicker.js",
        "locales/bootstrap-datepicker.ro.js",
        "detectmobilebrowser.js",
        "editor/ace.js",
        "jquery.easing.1.3.js",
        //'jquery.event.drag.live-2.2.js',
        'jquery.event.drag-2.2.js',
        //'jquery.event.drop.live-2.2.js',  
        'jquery.event.drop-2.2.js'
        );
    
    $arAdditionalLink[] = array("SHORTCUT ICON","./library/img/ws-icon1.png");
    $arAdditionalLink[] = array("icon","./library/ws-icon1.png","image/png");
    $arAdditionalLink[] = array("apple-touch-icon-precomposed","./library/img/ws-icon1.png","image/png");
    $arAdditionalLink[] = array("stylesheet",$_COMMON_CSS_FOLDER."bootstrap.min.css","text/css");
    $arAdditionalLink[] = array("stylesheet",$_COMMON_CSS_FOLDER."bootstrap-responsive.min.css","text/css");
    $arAdditionalLink[] = array("stylesheet",$_COMMON_CSS_FOLDER."bootstrap-datetimepicker.min.css","text/css");
    $arAdditionalLink[] = array("stylesheet",$_COMMON_CSS_FOLDER."datepicker.css","text/css");
    $arAdditionalLink[] = array("stylesheet","./library/font-awesome-4.0.3/css/font-awesome.min.css","text/css");
    $arAdditionalLink[] = array("stylesheet","./library/colorbox-master/example1/colorbox.css","text/css");
    $arAdditionalLink[] = array("stylesheet","./library/contextual-menu/jquery.contextMenu.css","text/css");
    $arAdditionalLink[] = array("stylesheet",$_COMMON_CSS_FOLDER."jquery.fileupload.css","text/css");
    
    $arAdditionalLink[] = array("stylesheet",$_COMMON_CSS_FOLDER."style.css","text/css");
    
    foreach ($_COMMON_JS_FILES as $index => $filename) {
       $arAdditionalScript[] = $_COMMON_JS_FOLDER.$filename; 
    }
    
    $arAdditionalScript[] = "./library/colorbox-master/jquery.colorbox-min.js";
    $arAdditionalScript[] = "./library/contextual-menu/jquery.ui.position.js";
    $arAdditionalScript[] = "./library/contextual-menu/jquery.contextMenu.js";
    $arAdditionalScript[] = $_COMMON_JS_FOLDER."jGravity.js";
    $arAdditionalScript[] = "http://connect.soundcloud.com/sdk.js";
    //$arAdditionalScript[] = $_COMMON_JS_FOLDER."jquery.fileupload.js";
    
    $arAdditionalScript[] = $_COMMON_JS_FOLDER."utils.js";
    $arAdditionalScript[] = $_COMMON_JS_FOLDER."script.js";
    
?>