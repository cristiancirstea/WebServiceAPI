<!DOCTYPE html>
<html>
<head>
<?php
    include_once dirname(__FILE__).'/IncludeFiles.php';
    
    foreach($arAdditionalLink as $index => $elem)
    {
        echo  '<link rel="'.$elem[0].'" href="'.$elem[1].'"'.(isset($elem[2]) ? 'type="'.$elem[2].'"' : "").'> ';
    }
    foreach($arAdditionalScript as $index => $elem)
    {
        echo  '<script src="'.$elem.'" type="text/javascript"></script> ';
    }
?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Web Service</title>
</head>
<body>
 
