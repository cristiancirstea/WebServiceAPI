<?php
require_once './library/database1.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBClass
 *
 * @author cristi_m
 */
class DBClass {
    private $_connection;
    private $_trans;
    public function __construct() 
     {
        $this->Connect();
        
    }   
   
    private function Connect()
    {
        try{
           dbSetTimeLimit(40);
            $this->_connection=  dbConnection();
            if ($this->_connection)
            $this->_trans= dbTransaction($this->_connection);
            return true;
        }
        catch (Exception $e)
        {
            throw new Exception("DB00001 Eroare la crearea conexiunii cu baza de date.".$e);
            dbCloseConnection();
            return false;
        }
    }
 /**
 * <b>Folosita la toate select-urile din baza!</b><br><br> 
 * Executa un text sql pentru selectarea datelor dintr-un tabel.<br>
 * Dupa executarea query-ului returneaza un vector de vectori asociativi. 
 * 
 * @param string $selectString Textul SQL 
 * 
 * @return Associative_array valoare=$result[index]["NUME_COLOANA"]
 * 
 * @throws Exception 
 */
    public function GetTable($queryStr)
    {
        if (!($this->_connection)||!($this->_trans))
        {
            if (!$this->Connect())
            {
                return null;
            }
        }
        $dataArr=array();
        try
        {
            $ResultTemp = dbQuery($this->_connection, $queryStr );
            $count=0;
            while ($dataRow= dbFetchAssoc($ResultTemp))
            {
                $dataArr[$count]=$dataRow;
                $count++; 
            }
            dbCommit($this->_connection);
            dbFreeResult($ResultTemp);
//             $file="text.txt";
//        file_put_contents($file,"\n - "."select" , FILE_APPEND | LOCK_EX);
        }
        catch (Exception $e)
        {
            throw new Exception("DB00002 Eroare la selectarea datelor din baza.". $e);
            if ($this->_trans)
            dbRollback($this->_trans);
             dbCloseConnection();
        }
            return $dataArr;
    }
    
 /**
 * <b>Folosita la toate executarile de query-uri in baza!</b><br><br>
 * Executa un text sql + commit.<br> 
 * 
 * @param string $executeString Textul SQL 
 * 
 * @return bool Daca s-a executat sau nu query-ul
 * 
 * @throws Exception
 */
    public function ExecuteStatement($executeString)
    {
        if (!($this->_connection)||!($this->_trans))
        {
            if (!$this->Connect())
            {
                return null;
            }
        }
        try
        {
            dbQuery($this->_connection, $executeString );
            $aBool= dbCommit($this->_connection);
            return $aBool;
        }
        catch(Exception $e)
        {
           throw new Exception("DB00003 Eroare la executarea comenzii.".$e);
           if ($this->_trans)
           dbRollback($this->_trans); 
            dbCloseConnection();
           return FALSE;
        }
    }
    public function SetTimeLimit($time)
    {
        dbSetTimeLimit($time);
    }
    public function __destruct() {
       try
        {
            dbCloseConnection($this->_connection);
        }
        catch(Exception $e)
        {
           throw new Exception("DB00004 Eroare la inchiderea conexiunii cu baza de date.". $e);
           if ($this->_trans)
           dbRollback($this->_trans); 
            dbCloseConnection();
           return FALSE;
        } 
        
    }
}

?>
