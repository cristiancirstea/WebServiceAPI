<?php
class APIClientBase 
{
	protected $_strEndpointURL = NULL;
	protected $_strEndpointUser = NULL;
	protected $_strEndpointPassword = NULL;
	
	public function __construct($strEndpointURL, $strEndpointUser = NULL, $strEndpointPassword = NULL)
	{
		$this->_strEndpointURL = trim($strEndpointURL);
		$this->_strEndpointUser = $strEndpointUser;
		$this->_strEndpointPassword = $strEndpointPassword;
	}
	
	public function setHTTPCredentials($strUser, $strPassword)
	{
		
		$this->_strEndpointUser = $strUser;
		$this->_strEndpointPassword = $strPassword;
	}

	public function encodeRequestParams($arrRequestParams, $strMethod = self::REQUEST_GET)
	{
		if ($arrRequestParams === NULL)
		{
			return "";
		}
		else
		{
			switch($strMethod)
			{
				case self::REQUEST_GET:
					return "?".http_build_query($arrRequestParams);
				default:
					return "";
			}
		}

	}


	protected function _APIServerCall($strFunctionName, $arrRequestParams = NULL, $strMethod = self::REQUEST_GET)
	{

		if(!$this->_cURLCheckBasicFunctions()){
			throw new Exception("cURL basic functions not found.",-1);
		}
		if (
				!in_array(
					$strMethod,
					array(
						self::REQUEST_GET,
						self::REQUEST_POST,
						self::REQUEST_PUT,
						self::REQUEST_DELETE
					)
				)
			)
		{
			throw new Exception("'".$strMethod."'method not supported");
		}
//		$strRequest = json_encode($arrRequestParams);
//		$strRequest = http_build_query($arrRequestParams);
		$arrHeader = array(
//				'Content-type: application/x-www-form-urlencoded'
				'Content-type: multipart/form-data'
//				"Content-Type: application/json",
//				'Content-Length: ' . strlen($strRequest)
			);

		$arrcURLOptions = array(
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT => 2,
			CURLOPT_HTTPAUTH => /*CURLAUTH_NTLM,*/CURLAUTH_BASIC,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			//CURLOPT_VERBOSE => 1,
			CURLOPT_FOLLOWLOCATION => false,
		);

		$strEndpointCall = $this->_strEndpointURL . "/" . trim($strFunctionName);
		if ($strMethod == self::REQUEST_GET)
			$strEndpointCall .= $this->encodeRequestParams($arrRequestParams, $strMethod);

		$cURL = curl_init();

		curl_setopt_array($cURL, $arrcURLOptions);
		
		curl_setopt($cURL, CURLOPT_HTTPHEADER, $arrHeader);
		curl_setopt($cURL, CURLOPT_URL, $strEndpointCall);
		curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, $strMethod);
		
		switch($strMethod)
		{
			case self::REQUEST_GET:

				curl_setopt($cURL,CURLOPT_POST, false);
				curl_setopt($cURL, CURLOPT_POSTFIELDS, NULL);
			break;
			case self::REQUEST_POST:
				//return json_encode(http_build_query(array("cae","asdsad")));
				curl_setopt($cURL, CURLOPT_POST, true);
				curl_setopt($cURL, CURLOPT_POSTFIELDS, $arrRequestParams);
			break;
			case self::REQUEST_PUT:
				curl_setopt($cURL,CURLOPT_POST, false);
				curl_setopt($cURL, CURLOPT_POSTFIELDS, json_encode($arrRequestParams));
			break;
			case self::REQUEST_DELETE:
				curl_setopt($cURL,CURLOPT_POST, false);
				curl_setopt($cURL, CURLOPT_POSTFIELDS, NULL);
			break;
		}

		if (isset($this->_strEndpointUser))
		{
			curl_setopt($cURL, CURLOPT_USERPWD, $this->_strEndpointUser.':'.$this->_strEndpointPassword);
		}
		else
		{
			curl_setopt($cURL, CURLOPT_USERPWD, NULL);
		}

		//return json_encode($arrcURLOptions);


		$strResponse = curl_exec($cURL);


		curl_close($cURL);
		
		return $strResponse;
	}
	
	private function _cURLCheckBasicFunctions() 
	{ 
	  if( !function_exists("curl_init") && 
		  !function_exists("curl_setopt") && 
		  !function_exists("curl_exec") && 
		  !function_exists("curl_close") ) 
	  {
		  return false; 
	  }
	  else 
	  {
		  return true; 
	  }
	}
	const REQUEST_GET = "GET";
	const REQUEST_POST = "POST";
	const REQUEST_PUT = "PUT";
	const REQUEST_DELETE = "DELETE";
}