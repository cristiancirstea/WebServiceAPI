<?php

include_once './API.php';
require_once './library/DBClass.php';
require_once './Security.php';
/**
 * Check string for Firebird.
 * 
 * <em>For now only commas</em>
 * 
 * @param String $str 
 * 
 * @return String 
 */
function FBCheckString($str)
{
    $result=str_replace("'", "''", $str);
    return $result;
}
function ArrayKey($key,$array,$defValue)
{
    if (array_key_exists($key, $array))
            return $array[$key];
    else
        return $defValue;
}
function MyFormatFloat($aFloat,$decimals = 2,$dec_point = '.',$thousands_sep = ',' )
{
    return number_format($aFloat,$decimals,$dec_point,$thousands_sep);
}
function MyFormatDate($aDateString,$format='d.m.Y') /* 'H:i' - pt ora*/
{
   $aDate=new DateTime(''.$aDateString);
   return $aDate->format($format);
}
class MyAPI extends API
{
	protected $DEBUG=true;
    protected $User;
    protected $_goodUser;
    protected $_userID;
    protected $_adminRights;
	protected $noKeyMethods=array('login','logout','test_connection');
	
    public function __construct($request, $origin) {
        parent::__construct($request);
        $this->_goodUser=false;
        $this->_userID=0;
        $this->_adminRights=false;
		
       if (strtoupper($this->method)=="PUT" || strtoupper($this->method)=="DELETE")
        {
           if (!$this->request)
           {
               $this->request=array();
           }
           //var_export($this->request);
           //PUT params are considered in JSON format!!! 
           $putParams=  json_decode($this->file,1);
           //$this->request=array_merge( $this->request,$putParams);
           unset($putParams);
        }
        if (!$this->DEBUG)
		{
		}
    }
    
    /**
     * Endpoints: /@method_name (".../service/@method_name[/@argument1/@argument2...][?param_request=val_param_request...]")
     */
	 //TODO
    protected function login()
    {
        if (!array_key_exists('user', $this->request)) 
            {
               Security::_ThrowError('WS00003',' User Error! '."'user'".' not sent!','Username not sent!');
                return false;
            }
        if (!array_key_exists('passw', $this->request)) 
            {
                Security::_ThrowError('WS00004',' Password Error! '."'passw'".' not sent!',"Password not sent!");
                return false;
            }
		//TODO check username and password
		return ($this->request['user']==='admin') && ($this->request['passw']==='a');

        
    }
	//TODO
    protected function logout(/*$key*/)
    {
         return true;
    }
	
    protected function test_connection(/*$params*/)
    {
        return true;
    }
	//for test recived multiple params
	protected function functionMParam($param1,$param2,$param3)
	{
		return array($param1,$param2,$param3,"req"=>array($this->request));
	}
	//for test recived array of params
	protected function functionArrParam($params)
	{
		return array($params,"req"=>array($this->request));
	}
}
?>
