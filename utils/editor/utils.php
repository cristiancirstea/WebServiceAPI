<?php
class Utils
{
    const _DEF_USER_CONFIG_FILE = './user_config.json';
    public static function GetDirContent($dir_path)
    {
        $dirList = array();
        $fileList = array();
        $rootFolder = $dir_path;
        if ($handle = opendir($rootFolder)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry!=="." && $entry!=="..")
                {
                    if (is_dir($rootFolder.$entry)){
                        $dirList[] = $entry;
                    }
                    else
                    {
                        $fileList[] = $entry;
                    }
                    
                }
            }
        }
        return array("dirList" => $dirList,"fileList" => $fileList);
    }
    public static function SaveUserConfig($theObj,$fileName)
    {
        if (!isset($fileName))
            $fileName = self::_DEF_USER_CONFIG_FILE;
       return file_put_contents($fileName, json_encode($theObj,JSON_PRETTY_PRINT));
    }
    public static function GetUserConfig($fileName)
    {
        if (!isset($fileName))
            $fileName = self::_DEF_USER_CONFIG_FILE;
        $jsonString = file_get_contents($fileName);
        return json_decode($jsonString);
    }
    public static function GetUserConfigString($fileName)
    {
        if (!isset($fileName))
            $fileName = self::_DEF_USER_CONFIG_FILE;
        return  file_get_contents($fileName);
    }
    
    public static function GetHiddenItems()
    {
        $theObj = self::GetUserConfig(null);
         if (property_exists($theObj,"hidden_files"))
         {
            return $theObj->hidden_files;
         }
         return array();
    }
    public static function GetUser($u_name,$pass)
    {
        $theObj = self::GetUserConfig(null);
        if (property_exists($theObj,"allowed_users"))
        {
            foreach($theObj->allowed_users as $index => $user)
            {
                if (property_exists($user,"username") && property_exists($user,"password"))
                {
                    if (($user->username == $u_name) && ($user->password == $pass))
                    {
                        return $user;
                    }
                }
            }
        }
        return null;
    }
    public static function GetThemeForUser($user_id)
    {
        $theObj = self::GetUserConfig(null);
        $theTheme = "xcode"; //default from here
        if (property_exists($theObj,"user_themes"))
        {
            foreach($theObj->user_themes as $index => $theme)
            {
                if (property_exists($theme,"user_id"))
                {
                    if ($theme->user_id == -1)
                    {
                        if (property_exists($theme,"theme"))
                        {
                            $theTheme =  $theme->theme;
                            break;
                        }
                    }
                    if ($theme->user_id == $user_id)
                    {
                        if (property_exists($theme,"theme"))
                        {
                            $theTheme =  $theme->theme;
                            break;
                        }
                    }
                }
            }
        }
        return preg_replace("/[\r\n]+/", "\n",$theTheme);
    }
}

?>