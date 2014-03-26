<?php

/**
 * Description of Security
 *
 * @author cristi_m
 */
class Security {
    
    private $_tempKEY;
    private $_userID;
    private $_adminRights;
    private $db;
    private $errorLogFile;
    public $showErrorCodes;
    public function __construct() {
        $this->_userID=0;
        $this->_adminRights=false;
        $this->db= new DBClass();
    }
    public function __destruct() {
        unset($this->db);
    }
    public function GetKey(){return $this->_tempKEY;}
    public function GetUserID(){ return $this->_userID;}
    public function GetAdminRights(){ return $this->_adminRights;}
    /**
     * 
     * @param String $errCode           Codul erorii
     * @param String $message           Mesajul "aruncat"
     * @param Boolean $writeInLog       Daca trebuie sa scrie in error log sau nu
     * @param Boolean $persistentCode   Daca afiseaza codul in eroarea returnata fara sa tina seama
     *                                  de variabila globala $errorCodes din config
     * @throws Exception
     */
    public static function _ThrowError($errCode,$message="",$logMessage="",$writeInLog=true,$persistentCode=false)
    {
        if ($logMessage==="")
        {
            $logMessage=$message;
        }
        $errorLogFile="error";
        if (!$persistentCode)
            $showErrorCodes=  (isset($GLOBALS["errorCodes"])&&($GLOBALS["errorCodes"]===true));
        else
            $showErrorCodes=true;
        if ($writeInLog)
        {
            try{
                $errorLogMessage= date('d.M.Y H:i:s')." - ".$errCode." : ".$logMessage;
                file_put_contents($errorLogFile,"# ".$errorLogMessage ."\n", FILE_APPEND | LOCK_EX);
            }
            catch (Exception $e)
            {
                throw new Exception("".$e);   
            }
        }
         throw new Exception(($showErrorCodes?$errCode." ":"")
            .$message);
    }
    
    public static function _ErrorLog($errCode,$message="")
 {
     $errorLogFile="error";
        try{
                $errorLogMessage= date('d.M.Y H:i:s')." - ".$errCode." : ".$message;
                file_put_contents($errorLogFile,"# ".$errorLogMessage ."\n", FILE_APPEND | LOCK_EX);
            }
            catch (Exception $e)
            {
                throw new Exception("".$e);   
            }
    }
    
	
	//TODO !!!!
    public function CheckKey($k,$checkLogIn=false)
    {
        try {
            if ($k!=='key')
             {
                 if ($checkLogIn===true)
                 {
                     $this->SaveKey (-1,1,$k);
                 }
                  return false;
             }
             else 
            {
                    $this->_userID=1;  
               return true;
            }
        }
        catch (Exception $e)
        {
            Security::_ThrowError("SE00001","Eroare la verificarea datelor de autentificare."/*.$e*/);
        }
    }
	//TODO save key !!!!!!!!!!!!!!
    private function SaveKey($user_id,$hoursActive=2,$alternateKey=null)
    {
        try {
           //TODO
          
        }
        catch (Exception $e)
        {
            Security::_ThrowError("SE00002","Eroare la verificarea datelor de autentificare."/*.$e*/);
        }
    }
	//TODO !!!!!!!!!!!!!!!!!
    public function LogOut($key)
    {
        $errName="";
        $errCode="";
         try {
 
        }
        catch (Exception $e)
        {
            $errCode="SE00004";
                $errName="Eroare de autentificare."/*.$e*/;
        }
          if ($errCode!=="")
            {
                 Security::_ThrowError($errCode,$errName/*.$e*/);
                return false;
            }
         return true;
    }
	//TODO !!!!!!!!!!!!!!!!!!
    private function ClearKeys($ama_id,$all=false)
    {
        try {
           return true;
        }
        catch (Exception $e)
        {
            Security::_ThrowError("SE00005","Eroare de autentificare."/*.$e*/);
            return false;
        }
    }
	//TODO !!!!!!!!!!!!!!!!!!!!!!!
    public function CheckUser($u,$p,$ip)
    {
        $errorMessage="";
        $errorCode="";
         try {
           // get_users
           // if (count(@users_in_db)===0)
           // {
           //     $errorCode="SE00006";
           //     $errorMessage="Utilizatorul nu a fost gasit in baza de date.";
           // }
           // else
           // if (@check_password)
           // {
               
           //     $this->_tempKEY=$this->GenerateKey();
            //    $this->SaveKey(@user_id_from_db);
            //    $this->_userID=@user_id_from_db;
//                $this->_adminRights=@user_has_admin_right;
                //20 ESTE ID-UL PT JURNAL ONLINE

               //  $resultUser[0]["KEY"]=  $this->_tempKEY;
                
              //  $this->ClearKeys(@user_id_from_db);
            }
            else {
                $errorCode="SE00007";
                $errorMessage="Date de autentificare invalide.";
            }
        }
        catch (Exception $e)
        {
             Security::_ThrowError("SE00008", "Eroare la verificarea datelor de autentificare.","",true);
			return null;
        }
        if ($errorCode!=="")
        {
            Security::_ThrowError($errorCode,$errorMessage,"",true);
            return null;
       }
        
        if ($resultUser)
            return $resultUser;
        else
            return null;
    }
	
    private function psMicroTime()
    {
            list($usec, $sec) = explode(" ", microtime());
            return ((float)$usec + (float)$sec);
    }
    private function GenerateKey($lenKey=25)
     {
         //15=lungimea string-ului de timp
         $nrChar=$lenKey-15;
         $arChars=str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
        shuffle($arChars);
        $theChars=implode('',  array_slice($arChars, rand(0, 50-$nrChar),$nrChar));
        $theTime=  str_replace(".", implode('',array_slice($arChars, rand(0, 49),1)) , (string)  $this->psMicroTime());
            if (strlen($theTime)<15)
                for ($i=0;$i<(15-strlen($theTime));$i++)
                   $theTime.=0;
            if (strlen($theTime)>15)
                $theTime=  substr ($theTime, 0,15);
          
         $strKey=  $theTime.$theChars;
         $arKey= str_split($strKey);
         shuffle($arKey);
        return implode('',$arKey);
    }
}

?>
