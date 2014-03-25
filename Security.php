<?php

/**
 * Description of Security
 *
 * @author cristi_m
 */
class Security {
    
    private $_tempKEY;
    private $_amaID;
//    private $_dreptSuprem;
    private $db;
    private $errorLogFile;
    public $showErrorCodes;
    public static $_specialPassw="cGFyb2xh";
    public static $_specialFile="./library/database.php";
    public function __construct() {
        $this->_amaID=0;
        $this->_dreptSuprem=false;
        $this->db= new DBClass();
    }
    public function __destruct() {
        unset($this->db);
    }
    public function GetKey(){return $this->_tempKEY;}
    public function GetAmaID(){ return $this->_amaID;}
    public function GetDreptSuprem(){ return $this->_dreptSuprem;}
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
    
    public function CheckKey($k,$checkLogIn=false)
    {
        try {
            $sql=" select AMA_ID from JO_KEYS
                where (current_timestamp>dela) and (current_timestamp<la) and KEY_VALUE='$k'";
            $resultAMA=$this->db->GetTable($sql); 
             if (count($resultAMA)===0)  
             {
                 if ($checkLogIn===true)
                 {
                     $this->SaveKey (-1,1,$k);
                 }
                  return false;
             }
             else 
            {
                    $this->_amaID=$resultAMA[0]["AMA_ID"];
//                $this->_dreptSuprem=($resultAMA[0]["DREPT_SUPREM"]==1);  
               return true;
            }
        }
        catch (Exception $e)
        {
            Security::_ThrowError("SE00001","Eroare la verificarea datelor de autentificare."/*.$e*/);
            dbRollback();
            dbCloseConnection();
        }
    }
    private function SaveKey($ama_id,$hoursActive=2,$alternateKey=null)
    {
        try {
            $sql="insert into JO_KEYS (KEY_VALUE,DELA,LA,AMA_ID) values 
                    ('".(($alternateKey)?$alternateKey:$this->_tempKEY).
                    "',current_timestamp,"
                    . "dateadd($hoursActive hour to current_timestamp),"
                    . "$ama_id)";
            //Security::_ErrorLog("-1", $sql);
            $aBool=$this->db->ExecuteStatement($sql); 
            return $aBool;
        }
        catch (Exception $e)
        {
            Security::_ThrowError("SE00002","Eroare la verificarea datelor de autentificare."/*.$e*/);
            dbRollback();
            dbCloseConnection();
        }
    }
    public function LogOut($key)
    {
        $errName="";
        $errCode="";
         try {
            $sql="select ama_id from JO_KEYS where  key_value='$key'";
            $result=$this->db->GetTable($sql); 
            if (count($result)==0)
            {
                $errCode="SE00003";
                $errName="Cheie invalida.";
              
            }
            else
            {
                $aBool= $this->ClearKeys($result[0]["AMA_ID"],true);
                if (!$aBool)
                {
                    $errCode="SE00009";
                    $errName="Nu s-au putut sterge cheile!";
                }
            }
        }
        catch (Exception $e)
        {
            $errCode="SE00004";
                $errName="Eroare de autentificare."/*.$e*/;
            dbRollback();
            dbCloseConnection();
        }
          if ($errCode!=="")
            {
                 Security::_ThrowError($errCode,$errName/*.$e*/);
                return false;
            }
         return true;
    }
    private function ClearKeys($ama_id,$all=false)
    {
        try {
            $sql="delete from JO_KEYS where (AMA_ID=$ama_id";
            if (!$all)
                $sql.=" and ((current_timestamp<dela) or (current_timestamp>la))) ";
            else
                $sql.=")";
            $sql.=" or (AMA_ID=-1 and dela<ADDMONTH('now',-1)) ";
            $aBool=$this->db->ExecuteStatement($sql); 
            return $aBool;
        }
        catch (Exception $e)
        {
            Security::_ThrowError("SE00005","Eroare de autentificare."/*.$e*/);
            dbRollback();
            dbCloseConnection();
            return false;
        }
    }
    public function CheckUser($u,$p,$ip)
    {
        $errorMessage="";
        $errorCode="";
         try {
            $sql=" select prenume||' '||nume as avocat,ama_id,DREPT_SUPREM,passw from avocati_ma 
                where upper(username)=upper('".$u."') and activ=1";
            $resultUser=$this->db->GetTable($sql);
            if (count($resultUser)===0)
            {
                $errorCode="SE00006";
                $errorMessage="Utilizatorul nu a fost gasit in baza de date.";
            }
            else
            if (md5($resultUser[0]["PASSW"])===$p)
            {
               
                $this->_tempKEY=$this->GenerateKey();
                $this->SaveKey($resultUser[0]["AMA_ID"]);
                $this->_amaID=$resultUser[0]["AMA_ID"];
//                $this->_dreptSuprem=($resultUser[0]["DREPT_SUPREM"]==1);
                //20 ESTE ID-UL PT JURNAL ONLINE
                $sqlU=" SELECT LOGARE_ID FROM INSERT_ISTORIC_LOGARE
                    (".$resultUser[0]["AMA_ID"].",'$ip' , 20)";
                $resultLogID=$this->db->GetTable($sqlU);
                $resultUser[0]["LOGARE_ID"]=$resultLogID[0]["LOGARE_ID"];
                 $resultUser[0]["KEY"]=  $this->_tempKEY;
                unset ($resultUser[0]["PASSW"]);
                
                $this->ClearKeys($resultUser[0]["AMA_ID"]);
            }
            else {
                $errorCode="SE00007";
                $errorMessage="Date de autentificare invalide.";
            }
        }
        catch (Exception $e)
        {
             Security::_ThrowError("SE00008", "Eroare la verificarea datelor de autentificare.","",true);
            //throw new Exception("SE00008 Eroare la verificarea datelor de autentificare."/*.$e*/);
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
