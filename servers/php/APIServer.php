<?php

require_once(dirname(__FILE__)."/APIServerBase.php");

class APIServer extends APIServerBase
{
    protected $_strOrigin;

    public function __construct($arrRequest, $origin)
    {
        parent::__construct($arrRequest);

        $this->_strOrigin = $origin;
       if (strtoupper($this->_strMethod)=="PUT" || strtoupper($this->_strMethod)=="DELETE"){
           if (!$this->_arrRequest)
           {
               $this->_arrRequest = array();
           }
          // var_export($this->_arrRequest);
           //PUT params are considered in JSON format!!!
           $putParams = json_decode($this->file, 1);
           if ($putParams)
           $this->_arrRequest = array_merge( $this->_arrRequest, $putParams);
           unset($putParams);
        }
    }

    /*
     * Endpoints: /@strMethod_name (".../api/@strMethod_name[/@argument1/@argument2...][?param_request=val_param_request...]")
     */
	 


    /**
     *
     * @return bool
     */
    public function test_connection(/*$arrParams*/)
    {
        return true;
    }
	
    /**
     * Return the given string.
     * @param $strMessage
     * @return array
     * @public Ceva interesant. plus inca ceva altceva
     * asdsadsad asdsa ds adsa dskadnaskdnsa
     * dsadsad
     * sadsad
     * sad. http://localhost/WebServiceAPI/servers/php/api/methods
     */
    public function echoMessage($strMessage)
	{
		return func_get_args();
	}

    /**
     * @param $arrParams
     * @return array
     */
	public function test_client($arrParams)
	{
		return array(
			"request" => $this->_arrRequest,
			"params" => $arrParams,
			"method" => $this->_strMethod,
			"user" => $this->_strUser,
			"password" => $this->_strPassword
		);
	}

    /**
     * @return array
     */
    public function methods()
    {
        return $this->exportPublicMethods(array());
    }


    /************************** API Public Methods************************/
    public function login()
    {
        return false;
    }


    public function logout($key, $ceva = NULL)
    {
        return false;
    }