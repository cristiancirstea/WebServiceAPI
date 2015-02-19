<?php
$param_name_file_recived = 'f_r';
$param_name_content_recived = 'c_r';
$param_name_new_file = 'n_file';
$param_name_new_folder = 'n_folder';
$param_name_switch_theme = 's_theme';
$param_name_user_name = 'u_name';
$param_name_delete_file = 'del_file';
$param_name_delete_directory = 'del_dir';
$theme_file_name = "./editor_theme.php";

include_once ($theme_file_name);

if (isset($_POST[$param_name_new_file])){
    $ourFileName = $_POST[$param_name_new_file];
    if (trim(ourFileName)!=''){
        $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
        fclose($ourFileHandle);
    }
}
else
  if (isset($_POST[$param_name_new_folder])){ 
      $path = $_POST[$param_name_new_folder];
      if (trim($path) != ''){
       if (!file_exists($path)) {
            mkdir($path, 0777, true);
        } 
      }
    }
    else
        if (isset($_POST[$param_name_file_recived])){ 
         $r = file_put_contents($_POST[$param_name_file_recived],$_POST[$param_name_content_recived] ) or die("can't open file");
        }
        else
            if (isset($_POST[$param_name_switch_theme])){ 
                /*$r = file_put_contents($theme_file_name, '<?php $_EDITOR_THEME = "'.$_POST[$param_name_switch_theme].'";?>') or die("can't open file");*/
				 if (isset($_POST[$param_name_user_name]))
					$r = file_put_contents($theme_file_name, GetThemeArrayStr($_POST[$param_name_user_name],$_POST[$param_name_switch_theme])) or die("can't open file");
				  else
					$r = file_put_contents($theme_file_name, GetThemeArrayStr("default",$_POST[$param_name_switch_theme])) or die("can't open file");
			}
			else
    			if (isset($_POST[$param_name_delete_file]))
    			{
    			    $r = unlink($_POST[$param_name_delete_file]) or die ("can't delete file ".$_POST[$param_name_delete_file]);
    			}
    			else
        			if (isset($_POST[$param_name_delete_directory]))
        			{
        			    $r = rmdir($_POST[$param_name_delete_directory]) or die ("can't delete dir ".$_POST[$param_name_delete_directory]);
        			}
                    else{
                        echo "Nimic de facut...";
                    }
            

function GetThemeArrayStr($user,$theme)
{
	global $_EDITOR_THEME;
	$_EDITOR_THEME[$user] = $theme;
	$str = '<?php $_EDITOR_THEME = array(';
	foreach($_EDITOR_THEME as $u => $th)
	{
		$str .= '"'.$u.'"=>"'.$th.'",';
	}
	if (!isset($_EDITOR_THEME["default"]))
		$str .= '"default"=>"clouds"';
	$str = rtrim($str,",");
	$str .= ');?>';
	return $str;
}