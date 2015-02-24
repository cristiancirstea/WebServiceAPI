<?php
include_once './APIServer.php';

processRequest();


/**
*
*/
function processRequest()
{
	if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
		$_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
	}

	try 
	{
		$API = new APIServer($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
		echo $API->processAPI();
		unset($API);
	} 
	catch (Exception $e) 
	{
		echo json_encode(
			array(
				'error' => $e->getMessage(), 
				'code' => $e->getCode()
			)
		);
	}
}