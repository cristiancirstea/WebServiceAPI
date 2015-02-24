<?php
require_once("./APIClientBase.php");
class APIClient extends APIClientBase
{
    public function __construct($strEndpointURL = "http://localhost/catalogul-online/server/api", $strEndpointUser = NULL, $strEndpointPassword = NULL)
    {
        parent::__construct($strEndpointURL, $strEndpointUser,$strEndpointPassword);
    }

    protected function _APIServerCall($strFunctionName, $arrQuery = NULL, $arrParams = NULL, $strMethod = self::REQUEST_GET)
    {
        //header("Content-Type: application/json");
        $strResult = parent::_APIServerCall($strFunctionName, $arrQuery, $arrParams, $strMethod);
        $mxResult = json_decode($strResult, true);
        if (!is_array($mxResult))
            return $mxResult;
        if (array_key_exists("error", $mxResult))
            throw new Exception($mxResult["error"]);
        if (array_key_exists("result", $mxResult))
            return $mxResult["result"];
        return $mxResult;
    }

    public function test_connection()
    {
        return $this->_APIServerCall(__FUNCTION__, null, self::REQUEST_GET);
    }

    public function test_client($arrParams)
    {
        $this->setHTTPCredentials("ghost","passw");
        return $this->_APIServerCall("test_client", $arrParams , self::REQUEST_POST);
    }

    
    public function _get($strMethodName, $arrParams)
    {
        return $this->_APIServerCall($strMethodName, $arrParams, self::REQUEST_GET);
    }


    public function _post($strMethodName, $arrParams)
    {
        return $this->_APIServerCall($strMethodName, $arrParams, self::REQUEST_POST);
    }


    public function _put($strMethodName, $arrParams)
    {
        return $this->_APIServerCall($strMethodName, $arrParams, self::REQUEST_PUT);
    }


    public function _delete($strMethodName, $arrParams)
    {
        return $this->_APIServerCall($strMethodName, $arrParams, self::REQUEST_DELETE);
    }
}

//TEST EXAMPLE
//$client = new APIClient("http://YourIP_OR_HOST/Server_PATH/api");
//var_dump(
//    $client->test_client(
//        array("ceva"=>1, "altceva"=>2)
//    )
//);