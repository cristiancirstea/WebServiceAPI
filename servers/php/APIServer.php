<?php

require_once(dirname(__FILE__)."/APIServerBase.php");
// require_once(dirname(__FILE__)."/library/db/DBClass.mysql.php");

class APIServer extends APIServerBase
{
    public function __construct($arrRequest, $origin) {
        parent::__construct($arrRequest);

       if (strtoupper($this->_strMethod)=="PUT" || strtoupper($this->_strMethod)=="DELETE"){
           if (!$this->arrRequest)
           {
               $this->arrRequest = array();
           }
           var_export($this->arrRequest);
           //PUT params are considered in JSON format!!!
           $putParams = json_decode($this->file,1);
           $this->arrRequest = array_merge( $this->arrRequest,$putParams);
           unset($putParams);
        }
    }

    /**
     * Endpoints: /@strMethod_name (".../service/@strMethod_name[/@argument1/@argument2...][?param_request=val_param_request...]")
     */
	 
    protected function login()
    {
        return false;
    }
	
    protected function logout(/*$key*/)
    {
         return false;
    }

    protected function test_connection(/*$arrParams*/)
    {
        return true;
    }

	protected function echoMessage($strMessage)
	{
		return func_get_args();
	}
	protected function test_client($arrParams)
	{
		return array(
			"request" => $this->_arrRequest,
			"params" => $arrParams,
			"method" => $this->_strMethod,
			"user" => $this->_strUser,
			"password" => $this->_strPassword
		);
	}
	protected function evenimente()
	{
		$db = new DBClass();
		$result = $db->GetTable("select * from evenimente");
		unset($db);
		return $result;
	}
}
?>
