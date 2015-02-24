<?php
    require_once '../_Common/library/config.mysql.php';
    
   function dbConnection(){
       $mysqli =  mysqli_connect($GLOBALS["host"],$GLOBALS['userBaza'], $GLOBALS["passwBaza"],$GLOBALS['numeBaza']);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: (" . mysqli_connect_errno() . ") " . mysqli_connect_error();
            return NULL;
        }
       return $mysqli;
   }
   
   function dbCloseConnection($connName)
   {
       $result=mysqli_close($connName) 
                or die("Eroare la inchiderea conexiunii. ". mysqli_error($connName));
       return $result;
   }
   
   function dbQuery($connection,$sql)
   {
       $result=  mysqli_query($connection,$sql) or die( mysqli_error($connection));
       return $result;
   }
   
   function dbAffectedRows($connection)
   {
       return mysqli_affected_rows($connection);
   }

   function dbFetchArray($result)
   {
       return mysqli_fetch_row($result);
   }
   function dbFetchAssoc($result)
   {
       return mysqli_fetch_assoc($result);
   }
   function dbFetchObject($result,$className=NULL,$params=NULL)
   {
       return mysqli_fetch_object($result,$className,$params);
   }

   function dbFreeResult($result)
   {
       return mysqli_free_result($result);
   }
   
   function dbCommit($connection)
   {
       return mysqli_commit($connection);
   }
   
   function dbRollback($connection)
   {
       return mysqli_rollback ($connection);
   }
   function dbLastErrorCode()
   {
       return mysqli_error_list();
   }
   function dbLastErrorMsg()
   {
       return mysqli_error();
   }
   function dbInsertID($connection)
   {
       return mysqli_insert_id($connection);
   }
?>

