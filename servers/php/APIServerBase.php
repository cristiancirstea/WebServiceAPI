<?php
require_once("APIServerException.php");
abstract class APIServerBase{
	/**
	*
	*/
	protected $_strUser = NULL;
	/**
	*
	*/
	protected $_strPassword = NULL;
	/**
	*
	*/
	protected $_bUseStrictParameters = true;
	/**
	* 
	*/
    protected $_returnFormatJSON = true;
     /**
     * Property: _strMethod
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $_strMethod = '';
    /**
     * Property: _strEndpoint
     * The Model requested in the URI. eg: /files
     */
    protected $_strEndpoint = '';
    /**
     * Property: verb
     * An optional additional descriptor about the endpoint, used for things that can
     * not be handled by the basic methods. eg: /files/process
     */
//    protected $verb = '';
    /**
     * Property: _arrArgs
     * Any additional URI components after the endpoint and verb have been removed, in our
     * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
     * or /<endpoint>/<arg0>
     */
    protected $_arrArgs = Array();
    /**
     * Property: file
     * Stores the input of the PUT request
     */
    protected $file = Null;
     /**
     * Constructor: __construct
     * Allow for CORS, assemble and pre-process the data
     */
    protected $_arrRequest=array();
	
	
    public function __construct($arrRequest) {
        
		header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
		
        if ($this->_returnFormatJSON){
            header("Content-Type: application/json");
        }
//        var_export($arrRequest);
        $this->_arrArgs = explode('/', rtrim($arrRequest, '/'));
        array_shift($this->_arrArgs);
        $this->_strEndpoint = array_shift($this->_arrArgs);
//        if (array_key_exists(0, $this->_arrArgs) && !is_numeric($this->_arrArgs[0])) {
//            $this->verb = array_shift($this->_arrArgs);
//            print_r($this->verb);
//            print_r($this->_arrArgs);
//        }

        $this->_strMethod = $_SERVER['REQUEST_METHOD'];
        if ( 
				($this->_strMethod == self::REQUEST_POST) 
					&& 
				(array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER))
			) 
		{
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == self::REQUEST_DELETE) 
			{
                $this->_strMethod = self::REQUEST_DELETE;
            } 
			else if ($_SERVER['HTTP_X_HTTP_METHOD'] == self::REQUEST_PUT) 
			{
                $this->_strMethod = self::REQUEST_PUT;
            } 
			else {
                throw new APIServerException("GWS0001 Unexpected Header");
            }
        }
        $this->file = file_get_contents("php://input");
		//TODO better handle the request params from $_POST or $_GET
        switch($this->_strMethod) {
//        case 'DELETE':
        case self::REQUEST_POST:
            $this->_arrRequest = $this->_cleanInputs($_POST);
            break;
        case self::REQUEST_GET:
            $this->_arrRequest = $this->_cleanInputs($_GET);
            break;
        case self::REQUEST_PUT:
        case self::REQUEST_DELETE:
            $this->_arrRequest = $this->_cleanInputs($_GET);
            //parse_str($this->file,$this->_arrRequest);
            break;
        default:
            $this->_response('Invalid Method', 405);
            break;
        }
    }

	
	/**
	* Calls the function in $this->_strEndpoint like this:
	* 	- if it has many parameters (ex: foo($param1,$param2,...)) 
	*		-> checks if was sent exactly the same number of params calls with each params		
	* 	- if it has only one parameter (ex: foo($param) )
	*		-> if first parameter contains "arr" passes all params as array
	*		-> else passes value of first param
	* @Throws error if less or more params was sent
	*/
    public function processAPI() 
	{
		$this->_setHTTPCredentials();
		//TODO add try catch for function call
		//TODO Throw ERROR_CODES!!!
        if ((int)method_exists($this, $this->_strEndpoint) > 0) {
           // return $this->_response($this->{$this->_strEndpoint}($this->_arrArgs));
		   
			$fct = new ReflectionMethod($this, $this->_strEndpoint);

			if (!$fct->isPublic())
				throw new APIServerException("Method "."'".$this->_strEndpoint."'"." not found!",APIServerException::METHOD_NOT_PUBLIC);

			$nParamsNr = $fct->getNumberOfRequiredParameters();
			$arrParamsNames = array();
			foreach($fct->getParameters() as $nIndex => $param)
			{
				$arrParamsNames[] = $param->name;
			}
			//if method expects more than one parameter
			
			if ($nParamsNr > 1)
			{
				if ($this->_bUseStrictParameters)
				{
					if (count($this->_arrArgs) < $nParamsNr)
					{
						$strParams = "$".$arrParamsNames[0];
						for($nIndex = 1; $nIndex < count($arrParamsNames); $nIndex++ )
							$strParams .= ", $".$arrParamsNames[$nIndex];
						throw new APIServerException("Not enough parameters for " . $this->_strEndpoint. "(".$strParams.")",
								APIServerException::PARAMS_LESS
							);
					}
					else
						if (count($this->_arrArgs) > $nParamsNr)
						{
							$strParams = "$".$arrParamsNames[0];
							for($nIndex = 1; $nIndex < count($arrParamsNames); $nIndex++ )
								$strParams .= ", $".$arrParamsNames[$nIndex];
							throw new APIServerException("Too many parameters for " . $this->_strEndpoint. "(".$strParams.")",
									APIServerException::PARAMS_MORE
								);
						}
				}
				else
				{
					//optional can set the extra parameters to null and call the function
					// //we makes sure that we call the method with all required parameters
					for( $i = count($this->arrArgs); $i < $nParamsNr; $i++)
					{
						$this->arrArgs[$i] = null;
					}
				}
				return $this->_response(
						call_user_func_array(
							array($this, $this->_strEndpoint), $this->_arrArgs)
						);
			}
		   else 
			if ($nParamsNr === 1)
			{
				$strParamName = $arrParamsNames[0];
				//if start with "arr" sends all parameters 
				if (strrpos($strParamName, "arr", -strlen($strParamName)) !== FALSE )
					return $this->_response($this->{$this->_strEndpoint}($this->_arrArgs));
				else
					if ($this->_bUseStrictParameters)
					{
						if (count($this->_arrArgs) > 1)
							throw new APIServerException("Too many parameter for ".$this->_strEndpoint.". Expected only ".$strParamName,
								APIServerException::PARAMS_MORE
							);
						else
							if (count($this->_arrArgs) == 0)
								throw new APIServerException("Expected: ".$strParamName." for ".$this->_strEndpoint,
										APIServerException::PARAMS_LESS
									);
					}
				return $this->_response($this->{$this->_strEndpoint}($this->_arrArgs[0]));
			}
			else
			{
				if ($this->_bUseStrictParameters)
				{
					if (count($this->_arrArgs) > 0)
						throw new APIServerException("Not expecting any parameters for ".$this->_strEndpoint,
								APIServerException::PARAMS_MORE
							);
				}
				return $this->_response($this->{$this->_strEndpoint}());
			}
        }
		throw new APIServerException("Method "."'".$this->_strEndpoint."'"." not found!",APIServerException::HTTP_404);
    }
	
	
    private function _response($mxData, $nStatus = 200) {
        header("HTTP/1.1 " . $nStatus . " " . $this->_requestStatus($nStatus));
        $arrResponse = array(
			"result" => $mxData,
			"count" => (isset($mxData) ? count($mxData) : 0),
			"type" => gettype($mxData),
			"status"=>$nStatus
			//TODO add extra information
		);
		return json_encode($arrResponse);
    }

	
    private function _cleanInputs($arrData) {
        $arrCleanInput = Array();
        if (is_array($arrData)) {
            foreach ($arrData as $strKey => $mxValue) {
                $arrCleanInput[$strKey] = $this->_cleanInputs($mxValue);
            }
        } else {
            $arrCleanInput = trim(strip_tags($arrData));
        }
        return $arrCleanInput;
    }

	
    private function _requestStatus($nCode) {
        $arrStatus = array( 
            100 => 'Continue',   
            101 => 'Switching Protocols',   
            200 => 'OK', 
            201 => 'Created',   
            202 => 'Accepted',   
            203 => 'Non-Authoritative Information',   
            204 => 'No Content',   
            205 => 'Reset Content',   
            206 => 'Partial Content',   
            300 => 'Multiple Choices',   
            301 => 'Moved Permanently',   
            302 => 'Found',   
            303 => 'See Other',   
            304 => 'Not Modified',   
            305 => 'Use Proxy',   
            306 => '(Unused)',   
            307 => 'Temporary Redirect',   
            400 => 'Bad Request',   
            401 => 'Unauthorized',   
            402 => 'Payment Required',   
            403 => 'Forbidden',   
            404 => 'Not Found',   
            405 => 'Method Not Allowed',   
            406 => 'Not Acceptable',   
            407 => 'Proxy Authentication Required',   
            408 => 'Request Timeout',   
            409 => 'Conflict',   
            410 => 'Gone',   
            411 => 'Length Required',   
            412 => 'Precondition Failed',   
            413 => 'Request Entity Too Large',   
            414 => 'Request-URI Too Long',   
            415 => 'Unsupported Media Type',   
            416 => 'Requested Range Not Satisfiable',   
            417 => 'Expectation Failed',   
            500 => 'Internal Server Error',   
            501 => 'Not Implemented',   
            502 => 'Bad Gateway',   
            503 => 'Service Unavailable',   
            504 => 'Gateway Timeout',   
            505 => 'HTTP Version Not Supported'); 
        return ( $arrStatus[$nCode]) ? $arrStatus[$nCode] : $arrStatus[500]; 
    }
	
	/**
	*
	*/
	private function _setHTTPCredentials()
	{
		if (array_key_exists('PHP_AUTH_USER', $_SERVER))
			$this->_strUser = $_SERVER['PHP_AUTH_USER'];
		else
		{
			$this->_strUser = NULL;
			$this->_strPassword = NULL;
			return false;
		}
		if (array_key_exists('PHP_AUTH_PW', $_SERVER))
			$this->_strPassword = $_SERVER['PHP_AUTH_PW'];
		return true;
	}


	protected function exportPublicMethods($arrExcludedMethods)
	{
		$rClass = new ReflectionClass(get_called_class());
		$nMethodNr = 0;
		$nParamNr = 0;
		$arrMethods = array();
		$arrExcludedMethods = array_merge($arrExcludedMethods,array("__construct", "processAPI",__FUNCTION__));

		foreach ($rClass->getMethods(ReflectionMethod::IS_PUBLIC) as $objMethod)
		{
			if(in_array($objMethod->getName(), $arrExcludedMethods))
			{
				continue;
			}

			$arrParams = array();
			$params = $objMethod->getParameters();
			foreach ($params as $objParam)
			{
				if ($objParam->isOptional())
				{
					$arrParams[] = array(
						"name" => $objParam->getName(),
						"default" => $objParam->getDefaultValue()
					);
				}
				else
				{
					$arrParams[] = $objParam;
				}
			}
			//TODO use @public and @return tags
			/*$strPublicDoc = $objMethod->getDocComment();
			if ($strPublicDoc)
			{
				$arrPublicDoc = explode("@public", $strPublicDoc);
				if (count($arrPublicDoc) > 1)
				{
					$strPublicDoc = str_replace("*"," ",$arrPublicDoc[1]);
					$strPublicDoc = trim(trim($strPublicDoc,"*\/\t "));
				}
			}*/

			$arrMethods[$nMethodNr] = array(
				"name" => $objMethod->getName(),
				"params" => $arrParams,
				//TODO
//				"DocComment" => $strPublicDoc
				//"uri" =>
			);
			$nMethodNr++;
		}

		return $arrMethods;
	}

	const REQUEST_GET = "GET";
	const REQUEST_POST = "POST";
	const REQUEST_PUT = "PUT";
	const REQUEST_DELETE = "DELETE";
}
