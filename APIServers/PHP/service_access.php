<?php
include_once './APIClient.php';

if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
   // echo $_REQUEST['request'];
    $API = new APIClient($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(
			array(
					'error' => $e->getMessage(), 
					'code' => $e->getCode()
				)
			);
}
?>
