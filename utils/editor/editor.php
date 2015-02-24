<?php
// <editor-fold defaultstate="collapsed" desc="Session check User">
if(session_status()!=PHP_SESSION_ACTIVE)session_start();
//if (isset($_SESSION["u_obj"]))
//    var_dump($_SESSION["u_obj"]);
if (isset($_SESSION["u_valid"])){
   if ($_SESSION["u_valid"]){
       $_U_NAME =  $_SESSION["u_obj"]->username;
   }
   else{
       header("location:".dirname(__FILE__).'./index.php'); 
   }
}
else{
       header("location:".dirname(__FILE__).'./index.php');
}
function IsAdminUser(){
    return isset($_SESSION["u_obj"]) ? ($_SESSION["u_obj"]->admin_rights) : false;
}
// </editor-fold>

include dirname(__FILE__).'/include/header.php';
echo '<script>'.
        '__USER_CONFIG__ = '.Utils::GetUserConfigString(null).';'
        .'delete(__USER_CONFIG__.allowed_users);'
        .'delete(__USER_CONFIG__.hidden_files);'
        .'</script>';

$_DEFAULT_ROOT_FOLDER = Utils::GetUserConfig(null)->editor_root_folder;
$_EDITOR_THEME =  Utils::GetThemeForUser($_SESSION["u_obj"]->user_id);
    
 $name_page_param = 'p';
 $name_folder_param = 'f';
 $name_ext_param = 'e';
 
 $_MODE_EDITOR = isset($_REQUEST[$name_page_param]);
 $_MODE_FOLDER_LIST = !$_MODE_EDITOR; //isset($_REQUEST[$name_folder_param])
 
 function ConcatRootFolder($dir){
     global $_DEFAULT_ROOT_FOLDER;
     return $_DEFAULT_ROOT_FOLDER.str_replace('./', '', $dir);
 }
?>
<div class="navbar navbar-fixed-top" id="navbar-editor" style="/*opacity: 0.8;*/">
  <div class="navbar-inner">   
    <a class="brand" id="brand-editor" href="editor">Editor</a>
    <ul class="breadcrumb my-breadcrumb">
            <?php 
            //handle request
                $FILE_NAME = '';
                $DISPLAY_FILE_NAME = '';
                $DISPLAY_FOLDER_NAME = './';
                $CURRENT_FOLDER = $_DEFAULT_ROOT_FOLDER;
                if (isset($_REQUEST[$name_page_param])){
                    $URI = $_REQUEST[$name_page_param];
                    $DISPLAY_FILE_NAME = $_REQUEST[$name_page_param];
                    $FILE_NAME = ConcatRootFolder($DISPLAY_FILE_NAME);
                }
                else
                     if (isset($_REQUEST[$name_folder_param])){
                            $URI = $_REQUEST[$name_folder_param];
                            $DISPLAY_FOLDER_NAME = $_REQUEST[$name_folder_param];
                            $CURRENT_FOLDER = ConcatRootFolder($DISPLAY_FOLDER_NAME);
                        }
                     else{
                        $URI = './';  
                     } 
               $arElem = explode('/', $URI);
               if ($arElem[count($arElem)-1]==''){
                   unset($arElem[count($arElem)-1]);
               }
                $nrElem = count($arElem);
                $strURI = '';
                foreach ($arElem as $index => $elem) {
                  $strURI .= $elem.'/';
                  if ($elem == '.'){
                      $elem = 'Root';
                  } 
                  if ($index==$nrElem-1){
                    echo '<li class="active">'.$elem.' </li>';
                  }
                  else{
                    echo '<li><a href="./editor?f='.$strURI.'">'.$elem.'</a> <span class="divider">/</span></li>';  
                  }   
                }
            ?>
            
    </ul> 
    <ul class="nav pull-right action-settings">
        <li class="dropdown">
            <a class="dropdown-toggle cursor-pointer" data-toggle="dropdown" title="Settings" >
                <?php if(isset($_U_NAME)) echo $_U_NAME.' ';?><i class="icon-cog"></i>
            </a>
            <ul class="dropdown-menu">
                <li class="dropdown-submenu">
                    <a class="cursor-pointer" >Editor Theme</a>
                     <ul class="dropdown-menu">
                        <li class="dropdown-submenu">
                            <a class="cursor-pointer" >Subset 1</a>
                                 <ul class="dropdown-menu">
                                        <li><a class="cursor-pointer item-theme" theme-name="ambiance">Ambiance</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="chaos">Chaos</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="chrome">Chrome</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="clouds">Clouds</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="clouds_midnight">Clouds Midnight</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="cobalt">Cobalt</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="crimson_editor">Crimson Editor</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="dawn">Dawn</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="dreamweaver">Dreamweaver</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="eclipse">Eclipse</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="github">Github</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="idle_fingers">Idle Fingers</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="katzenmilch">Katzenmilch</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="kr_theme">Kr Theme</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="kuroir">Kuroir</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="merbivore">Merbivore</a></li> 
                                 </ul>
                        </li>
                        <li class="dropdown-submenu">
                            <a class="cursor-pointer" >Subset 2</a>
                                 <ul class="dropdown-menu">
                                         <li><a class="cursor-pointer item-theme" theme-name="merbivore_soft">Merbivore Soft</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="mono_industrial">Mono Industrial</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="monokai">Monokai</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="pastel_on_dark">Pastel On Dark</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="solarized_dark">Solarized Dark</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="solarized_light">Solarized Light</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="terminal">Terminal</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="textmate">Textmate</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="tomorrow">Tomorrow</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="tomorrow_night">Tomorrow Night</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="tomorrow_night_blue">Tomorrow Night Blue</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="tomorrow_night_bright">Tomorrow Night Bright</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="tomorrow_night_eighties">Tomorrow Night Eighties</a></li> 
                                        <li><a class="cursor-pointer item-theme" theme-name="twilight">Twilight</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="vibrant_ink">Vibrant Ink</a></li>  
                                        <li><a class="cursor-pointer item-theme" theme-name="xcode">XCode</a></li>
                                 </ul>
                        </li>
                    </ul>
                </li>
                <li><a class="cursor-pointer">Others...</a></li>
                <li><a class="cursor-pointer" href="./">Log Out</a></li>
            </ul>
        </li>
      </ul>
      <script>
        $(".item-theme").on("click",function(){
            switchTheme($(this).attr("theme-name"));
        });
        $(".item-theme[theme-name='<?php  echo $_EDITOR_THEME ; ?>']").addClass("active").html(function(){
            return '<i class="icon-ok"></i> '+$(this).html();
        });
        switchTheme = function(theTheme,user){
		if (typeof(user) == "undefined")
			user = <?php if (isset($_U_NAME)) echo '"'.$_U_NAME.'"'; else echo '"default"'?>;
          var resp = $.post("writer.php", 
                        {  
                            s_theme : "" + theTheme,
							u_name 	: "" + user
                        },
                        function() {
                                // add error checking
                                setTimeout(function(){
                                    location.reload();
                                },100);
                        }
                );   
				console.log(resp);
        };
      </script>
    <?php
        if ($_MODE_EDITOR){
            ?>
            <ul class="nav pull-right action-file">
                  <li >
                       <button class="btn btn-action-editor" id="btn-edit-file"><i class="icon-pencil" title="Edit(Ctr + Shift + E)"></i> Edit</button>
                  </li>
                  <li >
                       <button class="btn btn-action-editor btn-info span1" id="btn-save-file" title="Save(Ctr + S)"><i class="icon-ok icon-white"></i> Save</button>
                  </li>
              </ul>
            <?php 
        }
        else{
            ?>
            
            <ul class="nav pull-right action-folder">
            <!-- hidden -->
                <li>
                  <div class="input-append" id="container-new-file">
                      <input type="text" id="input-new-file" placeholder="Name of the new File" />
                      <button class="btn btn-success" id="btn-save-new-file">
                        <i class="icon-ok icon-white"></i>
                      </button>
                  </div>
                </li>
                <li>    
                  <div class="input-append" id="container-new-folder">
                      <input type="text" id="input-new-folder" placeholder="Name of the new Folder" />
                      <button class="btn btn-info" id="btn-save-new-folder">
                        <i class="icon-ok icon-white"></i>
                      </button>
                  </div>
                </li>
        <!--end hidden -->
                <li >
                     <div class="btn-group pull-right">
                      <button class="btn dropdown-toggle btn-success" data-toggle="dropdown" id="btn-f-add">
                        Add
                        <span class="caret add-caret" style="border-top-color: white; border-bottom-color: white;"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="cursor-pointer" id="btn-new-file"><i class="icon-file"></i> File</a></li>
                        <li><a class="cursor-pointer" id="btn-new-folder"><i class="icon-folder-open"></i> Folder</a></li>
                      </ul>
                    </div>
                      
                </li>
             </ul>
            <?php
        }
      ?>
      
  </div>
</div>
<div class="" id="body-container-editor">

    <?php
    
    if ($_MODE_FOLDER_LIST){
        $dirList = array();
        $fileList = array();
       echo '<div class="container-folder-files">';
        if ($handle = opendir($CURRENT_FOLDER)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry!=="." && $entry!==".." && ( (IsAdminUser()) || (!IsAdminUser() && (!in_array($CURRENT_FOLDER.$entry,Utils::GetHiddenItems()))) ))
                {
                    if (is_dir($CURRENT_FOLDER.$entry)){
                        $dirList[] = $entry;
                    }
                    else
                    {
                        $fileList[] = $entry;
                    }
                    
                }
            }
        } 
        foreach($dirList as $index => $value){
            echo "<a href='./editor?$name_folder_param=".$DISPLAY_FOLDER_NAME.$value."/' class='folder' dir=".'"'.$DISPLAY_FOLDER_NAME.$value.'"'.">"
                              . "<i class='icon-folder-open'></i>"
                              . "$value"
                              . "</a>"
                              . "<br>";    
        }
        foreach($fileList as $index => $value){
             $ext = pathinfo($value, PATHINFO_EXTENSION);
                      $fileType = 'text';
                      switch ($ext){
                          case 'js':
                              $fileType = 'javascript';
                              break;
                          case 'php':
                              $fileType = 'php';
                              break;
                          case 'css':
                               $fileType = 'css';
                              break;
                           case 'xml':
                               $fileType = 'xml';
                              break;
                          
                          default :
                              $fileType = $ext;
                              break;
                      }
						if (in_array(strtolower($ext),array('ico','png','jpg','jpeg','bmp','gif')))
						{
							echo "<a href=".'"'.$DISPLAY_FOLDER_NAME.'/'.$value.'"'." class='picture picture-$fileType' "
							//."onclick='$.colorbox({href:});'
							."file=".'"'.$DISPLAY_FOLDER_NAME.$value.'"'.">"
							. "<i class='icon-camera '></i> "
							. "$value"
							."</a>"
							."<br>";
						}
						else
						{
						  echo "<a href='./editor?$name_page_param=".$DISPLAY_FOLDER_NAME.$value."&$name_ext_param=$fileType ' "
								  . "class='file file-$fileType' file=".'"'.$DISPLAY_FOLDER_NAME.$value.'"'.">"
								  . "<i class='icon-file icon-white'></i> "
								  . "$value"
								  . "</a>"
								  . "<br>";  
						}
        }
        echo '</div>';
        
        ?>
        <script>
		
			jQuery('a.picture').colorbox({rel:"mainGroup"});
            var IS_EDITED = false;
            var INTERVAL_AUTOSAVE = 10000;
            $("#container-new-file").hide();
            $("#container-new-folder").hide();
            $("#btn-new-file").on("click",function(){
               $("#container-new-file").show();
               $("#input-new-file").focus();
               $("#btn-f-add").hide();
            });
            $("#btn-new-folder").on("click",function(){
               $("#container-new-folder").show();
               $("#input-new-folder").focus();
              $("#btn-f-add").hide();
            });
            $("#btn-save-new-file").on("click",function(){
              $("#btn-f-add").show();
               $("#container-new-file").hide(); 
              saveNewFile($("#input-new-file").val()); 
            });
             $("#btn-save-new-folder").on("click",function(){
              $("#btn-f-add").show();
               $("#container-new-folder").hide(); 
               saveNewFolder($("#input-new-folder").val());
            });
            saveNewFile = function(newFileName) {
                // var newFileName = $("#input-new-file").val();
                Console('New file->' + newFileName);
                if (newFileName.trim() == ''){
                    return;
                }
                $.post("writer.php", 
                        {  
                            n_file :<?php echo '"'.$CURRENT_FOLDER.'"';?> + newFileName },
                        function() {
                                // add error checking
                               ArataMesajAlerta("<span class='center'>Success!</span><br/>",
                                                "<span class='center'>File saved.</span>",
                                                "success",
                                                "",//"navbar-editor",
                                                false,
                                                1000);
                                setTimeout(function(){
                                    location.reload();
                                },1000);
                        }
                );
            };
            saveNewFolder = function(newFolderName) {
                // var newFolderName = $("#input-new-folder").val();
                Console('New folder->' + newFolderName);
                if (newFolderName.trim() == ''){
                    return;
                }
                $.post("writer.php", 
                        {  
                            n_folder :<?php echo '"'.$CURRENT_FOLDER.'"';?> + newFolderName },
                        function() {
                                // add error checking
                               ArataMesajAlerta("Success!<br/>",
                                                "Folder saved.",
                                                "success",
                                                "",//"navbar-editor",
                                                false,
                                                1000);
                                setTimeout(function(){
                                    location.reload();
                                },1000);
                        }
                );
            };
        </script>
        <?php
        
    }
    else{

        echo '<pre id="editor"'
                . ' class="editor"'
                .' fileType="'.(isset($_REQUEST[$name_ext_param])? ($_REQUEST[$name_ext_param]):'text').'"'
            .'>';
         echo htmlentities(file_get_contents($FILE_NAME));
        echo '</pre>';
        ?>
        <script>
        var _EDITOR_THEME = "<?php   echo $_EDITOR_THEME; ?>";
            ActivateEditor("editor",_EDITOR_THEME);
            $("title")[0].text = <?php echo '"WS -> '.ltrim($DISPLAY_FILE_NAME,'./').'";';?>
            var editor = ace.edit("editor");
            editor.setReadOnly(true);
            editor.commands.addCommand({
                name: 'saveFile',
                bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
                exec: function(editor) {
                   saveFile();
                },
                readOnly: true // false if this command should not apply in readOnly mode
            });
            editor.commands.addCommand({
                name: 'enableEdit',
                bindKey: {win: 'Ctrl-Shift-E',  mac: 'Command-E'},
                exec: function(editor) {
                    editor.setReadOnly(false);
                    $("#btn-edit-file").hide();
                    //AUTOSAVE!!!!!
                    enableAutoSave();
                },
                readOnly: true // false if this command should not apply in readOnly mode
            });
            editor.on("change",function(){
                IS_EDITED = true;
            });
            enableAutoSave = function(){
                setInterval(
                       function(){ 
                            if(IS_EDITED)
                            {
                                saveFile(true);
                            }
                           
                        }
                        ,INTERVAL_AUTOSAVE
                    );
            }
            saveFile = function(isAuto) {
                var editor = ace.edit("editor");
                var contents = editor.getSession().getValue();
                Console(contents);
                IS_EDITED = false;
                var showSavedMessage;
                if (isAuto === true)
                {
                    showSavedMessage = function() {
                              ArataMesajAlerta("Saved!",
                                                "",
                                                "success",
                                                "",//"navbar-editor",
                                                false,
                                                500);   
                            console.log("Auto Saved!");
                        };
                }
                else
                {
                    showSavedMessage = function() {
                                // add error checking
                               ArataMesajAlerta("Success!<br/>",
                                                "File saved.",
                                                "success",
                                                "",//"navbar-editor",
                                                false,
                                                2000);
                            console.log("Saved!");
                        };
                }
                
                $.post("writer.php", 
                        {   f_r : <?php echo '"'.$FILE_NAME.'"';?>,
                            c_r : contents },
                        showSavedMessage
                );
            };
            
            $("#btn-save-file").on("click",function(){
                saveFile();
            });
            $("#btn-edit-file").on("click",function(){
                 var editor = ace.edit("editor");
                 editor.setReadOnly(false);
                 $(this).hide();
                 enableAutoSave();
            });
        </script>
        <?php
    }

    ?>
</div>

<style>
    body{
        background-image: url('./library/img/back2.jpg');
        background-size: cover;
        background-repeat:no-repeat;
        background-attachment:fixed;
        background-position:center; 
    }
   
    
</style>

<?php
include dirname(__FILE__).'/include/footer.php';
?>
