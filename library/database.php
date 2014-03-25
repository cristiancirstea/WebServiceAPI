<?php
    require_once './library/config.php';
  
  ob_start(); 
   function dbCloseConnection($connName=null)
   {
       if ($connName)
       {
           $result=ibase_close($connName) 
                or die("Eroare la inchiderea conexiunii Firebird. " .ibase_errmsg()); 
           return $result;
       }
        else
        {
            ibase_close() or die("Eroare la inchiderea conexiunii Firebird. " .ibase_errmsg());
        }
          // throw new Exception("Invalid connection!".  ibase_errmsg());  
   }
   function dbSetTimeLimit($time)
   {
        set_time_limit($time);
   }
   function dbQuery($connection,$sql)
   {
       $result=  ibase_query($connection,$sql) or die(ibase_errmsg());
       return $result;
   }
   
   function dbAffectedRows($connection)
   {
       return ibase_affected_rows($connection);
   }

   function dbFetchArray($result,$resultType=IBASE_TEXT)
   {
       return ibase_fetch_row($result,$resultType);
   }
   function dbFetchAssoc($result,$resultType=IBASE_TEXT)
   {
       return ibase_fetch_assoc($result,$resultType);
   }
   function dbFetchObject($result,$resultType=IBASE_TEXT)
   {
       return ibase_fetch_object($result,$resultType);
   }

   function dbFreeResult($result)
   {
       return ibase_free_result($result);
   }
   
   function dbCommit($connection)
   {
       if ($connection)
        return ibase_commit($connection);
       else
           throw new Exception("Invalid connection!".  ibase_errmsg());
   }
   function dbTransaction($connection)
   {
       if ($connection)
        return ibase_trans();
       else
           throw new Exception("Invalid connection!".  ibase_errmsg());  
   }
   function dbRollback($trans)
   {
       if ($trans)
        return ibase_rollback_ret($trans);
       else
           throw new Exception("Invalid transaction!".  ibase_errmsg());
   }
   
    function dbConnection(){
        $passwBaza=  base64_decode($GLOBALS["passwBaza"]);
        $result= ibase_connect($GLOBALS['path'],$GLOBALS['userBaza'], $passwBaza());
              // or die ('Eroare de conectare Firebird. '.  ibase_errmsg());
        if (!$result)
        {
            ob_end_clean();
           throw new Exception('Eroare de conectare Firebird. '.  ibase_errmsg());
        }
        return $result;       
   }
   
   function dbLastErrorCode()
   {
       return ibase_errcode();
   }
   function dbLastErrorMsg()
   {
       return ibase_errmsg();
   }
     //---
   function dbColNum($result)
   {
       return ibase_num_fields($result);
   }
   function dbColInfo($result,$index)
   {
       return ibase_field_info($result, $index);
   }
   function _d39121117b_()
   {
       
       $key_const="SEtFWV9MT0NBTF9NQUNISU5F";
       $key1="U09GVFdBUkU=";
       $key2="Skc=";
       $key3="REJQYXNz";
       $wscript = new COM('WScript.Shell');
        $result= $wscript->RegRead(base64_decode($key_const)."\\".base64_decode($key1)."\\".
                base64_decode($key2)."\\JG2\\".base64_decode($key3)) or die ('Eroare de conectare Firebirdr.');
        return base64_decode($result);
   }
   function _d45121782b_()
   {
       $key_const="SEtFlF9MT4NBTF9NgUNISU5F";
       $key1="R09GVBdBJKU=";
       $key2="STc=j";
       $key3="REJLKNNz";
       $wscript = new COM('WScript.Shell');
        $result= $wscript->RegRead(base64_decode($key_const)."\\".base64_decode($key1)."\\".
                base64_decode($key2)."\\".base64_decode($key3)) or die ('Eroare de conectare Firebirdr.');
        return $result;
   }
   function _d3236459817b_()
   {
       $key_const="SEtFWV45f0NBTF9NQUNISU5F";
       $key1="TB09GV36rUkU=";
       $key2="Lpnc=45";
       $key3="RQ36G8XNz";
       $wscript = new COM('WScript.Shell');
        $result= $wscript->RegRead(base64_decode($key_const)."\\".base64_decode($key1)."\\".
                base64_decode($key2)."\\".base64_decode($key3)) or die ('Eroare de conectare Firebirdr.');
        return $result;
   }
   function dbColAttr($result,$index,$attrName='name')
   {
       $col_info = dbColInfo($result, $index);
       return $col_info[$attrName];
   }      
   function dbColsAttr($result,$attrName='name')
   {
       /*$attrName - 'name'
        *          - 'alias'
        *          - 'relation'
        *          - 'length'
        *          - 'type'
        */
       $fieldData=  array();
       $coln = dbColNum($result);
       for ($i = 0; $i < $coln; $i++) {
           $col_info = dbColInfo($result, $i);
           array_push($fieldData, $col_info[$attrName]);
       }
       return $fieldData;
   }
   //---
?>
