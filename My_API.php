<?php

include_once './API.php';
require_once './library/DBClass.php';
require_once './Security.php';
/**
 * Verifica daca stringul e bun pt a fi transmis in Firebird.
 * 
 * <em>Deocamdata verifica doar apostroafele</em>
 * 
 * @param String $str string-ul de verificat
 * 
 * @return String string-ul bun
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
    protected $aliasID;
    protected $aliasTEXT;
	protected $noKeyMethods=array('login','logout','test_connection');
	
    public function __construct($request, $origin) {
        parent::__construct($request);
        $this->_goodUser=false;
        $this->_userID=0;
        $this->_adminRights=false;
        $this->showErrorCodes=false;
        
//         if (strtoupper($this->method)=="DELETE")
//         {
//            if (!$this->request)
//           {
//               $this->request=array();
//           }
//           //Security::_ThrowError("-1", file_get_contents("php://input"));
//            $putParams=json_decode(file_get_contents("php://input"),1);
////           var_export($putParams);
//            $this->request=array_merge( $this->request,$putParams);
//            unset($putParams);
//         }
       if (strtoupper($this->method)=="PUT" || strtoupper($this->method)=="DELETE")
        {
           if (!$this->request)
           {
               $this->request=array();
           }
           //var_export($this->request);
           //Consider parametrii transmisi prin put in formatul JSON. 
           $putParams=  json_decode($this->file,1);
           //$this->request=array_merge( $this->request,$putParams);
           unset($putParams);
        }
        if (!$this->DEBUG)
		{
			if (array_key_exists('key', $this->request))
			{
			   $security= new Security();
				$this->_goodUser=$security->CheckKey($this->request["key"]);
				$this->_userID=$security->GetUserID();
				$this->_adminRights=$security->GetAdminRights();
			   unset($security);
			   //cheia nu e buna si ar trebui sa se logeze din nou sa-i generez alta
			   if (!$this->_goodUser)
			   {
				   Security::_ThrowError("WS00002","Eroare de autentificare. Cheie invalida!",
						   "Eroare de autentificare. Cheie invalida! - ".$this->request["key"],true,true);
				   return;
			   }
			   
			   if (array_key_exists("select2", $this->request))
			  {
					$this->aliasID="as ID";
					$this->aliasTEXT="as TEXT";
			   }
			   else
			   {
					$this->aliasID="";
					$this->aliasTEXT="";
			   }
			}    
			else
	//singurele functii care nu cer cheie ca parametru transmis prin GET/POST/PUT/DELETE sunt login si logout
	 //login primeste userul si parola, iar logout primeste cheia ca argument (".../logout/cheie")
			if (!in_array ($this->endpoint,$this->noKeyMethods))
			{
				Security::_ThrowError('WS00001',"Datele de autentificare nu au fost transmise .");
			}
		}
    }
    
    /**
     * Endpoints/Functii (".../service/nume_functie/argumente?param_request=val_param_request...")
     */
    protected function login()
    {
        $security= new Security();
        if (!array_key_exists('user', $this->request)) 
            {
               Security::_ThrowError('WS00003',' Datele de autentificare nu au fost transmise.',"LogIn:User netrimis!");
                return false;
            }
        if (!array_key_exists('passw', $this->request)) 
            {
                Security::_ThrowError('WS00004',' Datele de autentificare nu au fost transmise.',"LogIn:Parola netrimisa!");
                return false;
            }
        if (!array_key_exists('logKey', $this->request)) 
            {
                Security::_ThrowError('WS0000?',' Datele de autentificare nu au fost transmise.',"LogIn:LogInKey netrimisa!");
                return false;
            }
        $this->User=$this->request["user"];
        $password=$this->request["passw"];
        $ip=  (array_key_exists('ip', $this->request)?$this->request["ip"]:"-1");
              
            $sec=new Security();
        if ($sec->CheckKey($this->request["logKey"],true))
        {
           Security::_ThrowError('WSX000?',' Acces restrictionat! Datele de autentificare au fost retrimise!',
                   "LogInKey retrimisa!!!");
           //+salvarea ip-ului care a retrimis datele
                return false; 
        }
        unset($sec);
        $result=$security->CheckUser($this->User,$password,$ip);
         unset($security);
            if ($result!==null)
            {
                $this->_goodUser=true;
                return $result;
            }
            else 
            {
                Security::_ThrowError('WS00016','Date de autentificare invalide .');
            }
        
    }
    protected function logout($key)
    {
        if ((!$key)||(count($key)===0))
        {
            Security::_ThrowError("WS00005","Cheia de autentificare nu a fost trimisa."); 
            return;
        }
         $security= new Security();
            $result=$security->LogOut($key[0]);
         unset($security);
         return $result;
    }
    protected function test_connection(
//            $params
            )
    {
        return true;
    }
	
	protected function functionMParam($param1,$param2,$param3)
	{
		return array($param1,$param2,$param3,"req"=>array($this->request));
	}
	
	protected function functionArrParam($params)
	{
		return array($params,"req"=>array($this->request));
	}
}
?>