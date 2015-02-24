<?php
include_once dirname(__FILE__).'/../library/config.mysql.php';
if (isset($GLOBALS['show_PHPError'])){
    if ($GLOBALS['show_PHPError']===false){
        error_reporting(0);
    }
}
class Logger {
    public static $_LOG_DIR = 'Logs';
    public static $_OLD_LOG_DIR = 'Logs/Old';
    public static $_ERROR_LOG_FILE = 'error';
    public static $_DEF_LOG_FILE = 'log';
    
    public static function _LOG_DIR(){
        if (!file_exists(Logger::$_LOG_DIR))
        {
            mkdir(Logger::$_LOG_DIR);
        }
        return Logger::$_LOG_DIR;
    }
    public static function _OLD_LOG_DIR(){
       if (!file_exists(Logger::$_OLD_LOG_DIR))
        {
            mkdir(Logger::$_OLD_LOG_DIR);
        }
        return Logger::$_OLD_LOG_DIR; 
    }
    public static function _BackUpLogs(){
        try{
            $LogFile= Logger::_LOG_DIR().'/'.Logger::$_DEF_LOG_FILE;
            $erLogFile= Logger::_LOG_DIR().'/'.Logger::$_ERROR_LOG_FILE;
            $old_LogFile= Logger::_OLD_LOG_DIR().'/'.Logger::$_DEF_LOG_FILE.date('d.M.Y H-i-s').'.old';
            $old_erLogFile= Logger::_OLD_LOG_DIR().'/'.Logger::$_ERROR_LOG_FILE.date('d.M.Y H-i-s').'.old';
            
            copy($LogFile,$old_LogFile);
            copy($erLogFile,$old_erLogFile);
        } catch (Exception $ex) {
            Logger::_ThrowError($ex, 'LOG0001', "Can't backup logs!");
        }
    }
    public static function _ClearLogs(){
        try{
            Logger::_BackUpLogs();
            $LogFile= Logger::_LOG_DIR().'/'.Logger::$_DEF_LOG_FILE;
            $erLogFile= Logger::_LOG_DIR().'/'.Logger::$_ERROR_LOG_FILE;
            
            file_put_contents($LogFile,'');
            file_put_contents($erLogFile,'');
        } catch (Exception $ex) {
            Logger::_ThrowError($ex, 'LOG0002', "Can't clear logs!");
        }
    }
    public static function Log($Code,$message=""){
     $LogFile= Logger::_LOG_DIR().'/'.Logger::$_DEF_LOG_FILE;
        try{
                $errorLogMessage = date('d.M.Y H:i:s')." - ".$Code." : ".$message;
                file_put_contents($LogFile,"# ".$errorLogMessage ."\n", FILE_APPEND | LOCK_EX);
            }
            catch (Exception $e)
            {
                throw new Exception("".$e);   
            }
    }
    public static function _ErrorLog($errCode,$message=""){
     $errorLogFile=  Logger::_LOG_DIR().'/'.Logger::$_ERROR_LOG_FILE;
        try{
                $errorLogMessage = date('d.M.Y H:i:s')." - ".$errCode." : ".$message;
                file_put_contents($errorLogFile,"# ".$errorLogMessage ."\n", FILE_APPEND | LOCK_EX);
            }
            catch (Exception $e)
            {
                throw new Exception("".$e);   
            }
    }
    public static function _ThrowError( $e
            , $errCode
            ,$message = ''
            ,$logMessage = ''
            ,$persistentErrorCode = false){
        if (strlen(trim($logMessage)) === 0)
        {
            $logMessage = $message;
        }
        $errorLogFile = Logger::_LOG_DIR().'/'.Logger::$_ERROR_LOG_FILE;
        if (isset($GLOBALS["show_FullError"])&&($GLOBALS["show_FullError"]===true)){
            $showErrorCodes = true;
            $fullError = true;
        }
        else{
            $fullError = false;
            $showErrorCodes = $persistentErrorCode;
            if (!$persistentErrorCode)
            {
                 $showErrorCodes = (isset($GLOBALS["errorCodes"])&&($GLOBALS["errorCodes"]===true));
            }
        }
        try{
                $errorLogMessage = date('d.M.Y H:i:s')." - ".$errCode." : ".$logMessage;
                $errorLogMessage .= "\n----------------------------------------------------";
                if (isset($e))
                {
                    $errorLogMessage .= "\n".$e;//."\n".$e->getTraceAsString();
                }
                $errorLogMessage .= "\n----------------------------------------------------\n";
                file_put_contents($errorLogFile,"# ".$errorLogMessage ."\n", FILE_APPEND | LOCK_EX);
            }
            catch (Exception $e2)
            {
                throw new Exception("".$e2);   
            }
         throw new Exception( 
                 ($showErrorCodes ? $errCode . " " : "") . ($fullError ? $logMessage : $message)
                 );
    }
} 
?>