<?php
include_once './APIServer.php';

if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
   // echo $_REQUEST['request'];
    $API = new APIServer($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(
			array(
					'error' => $e->getMessage(), 
					'code' => $e->getCode()
				)
			);
}