<?php 
include dirname(__FILE__).'/include/header.php';
//include './user_config.php';

/*included in header.php*/
//include_once '....library/Utils.php';
$param_name_user = "u";
$param_name_passw = "p";
$_USER = null;

if(session_status()!=PHP_SESSION_ACTIVE)session_start();
$first_time = true;
$_SESSION["u_valid"] = false;

unset($_SESSION["u_l"]);
unset($_SESSION["u_obj"]);

if (isset($_POST[$param_name_user])){
    $_USER = Utils::GetUser($_POST[$param_name_user],$_POST[$param_name_passw]);
    //if (isset($_ALLOWED_USERS[$_POST[$param_name_user]]))
    // if ( $_ALLOWED_USERS[$_POST[$param_name_user]] == $_POST[$param_name_passw]){
   
   //// if (($_POST[$param_name_user] == "admin") && ($_POST[$param_name_passw] == "admin")){
      //  $_SESSION["u_l"] = $_POST[$param_name_user] ;
      if ($_USER !== null)
      {
        $_SESSION["u_l"] = $_USER->username;
        $_SESSION["u_obj"] = $_USER;
        $_SESSION["u_valid"] = true;
      //}
    }
    $first_time = false;
} 


?>

<!--<video autoplay="" loop id="back-video">
  <source src="https://s3.amazonaws.com/sci2.tv/video/supernova-9.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>--> 


<div class="title-container-login">
    <span id="title-login">Web Service Administration</span>
</div>
<div class="n-form-login " id="n_containerLogIn">
     <form action="./" method="post" class="">
        <div class="n-form-header-login">
            <span class="n-form-title-login">Sing In</span>
        </div>
        <div class="n-form-body-login">
             <div class="container-input center">
                <i class="fa fa-user icon-input"></i> 
                    <input type="text" class="n-input-login" id="input-login-user" name="<?php echo $param_name_user?>"
                        style="background: transparent;border: none; color: #DBDADA; height: 25px;"
                        autocomplete="off"/>
                </div>
                <div class="container-input center">
                    <i class="fa fa-lock icon-input"></i> 
                    <input type="password" class="n-input-login" id="input-login-password" name="<?php echo $param_name_passw?>"
                        style="background: transparent;border: none; color: #DBDADA; height: 25px;"/>
                </div>
                <div class="container-button-login ">
                    <button type="submit" class="btn btn-success btn-block center" id="btn-login">Log In</button>
                </div>
        </div>
        </form>
    </div>

<div class="body-container-login hidden" id="body-container-form-login">
    <form action="./" method="post" class="form-login center">
        <div class="header-form-login">
            <img src="./library/img/btn-close.png" class="pull-right hidden">
        </div>
        <div class="body-form-login">
            <div class="container-input-user center">
                <input type="text" class="input-login" id="input-login-user" name="<?php echo $param_name_user?>"
                    style="background: transparent;border: none; color: #DBDADA; height: 25px;"
                    autocomplete="off"/>
            </div>
            <div class="container-input-password center">
                <input type="password" class="input-login" id="input-login-password" name="<?php echo $param_name_passw?>"
                    style="background: transparent;border: none; color: #DBDADA; height: 25px;"/>
            </div>
            <div class="container-button-login ">
                <button type="submit" class="btn btn-success btn-block center" id="btn-login">Log In</button>
            </div>
        </div>
    </form>
</div>
<!-- TODO another php page-->
<div class="container body-container-main-menu" id="body-container-mm-login">
    <div class="panel-container-main-menu">
        <a href="./editor" class="span4 well panel-main-menu panel-main-menu-col1" id="panel-mm-editor">
                <img src="./library/img/edit-icon.png" class="img-rounded panel-icon"> 
                <span class="text-main-menu">Editor</span>
        <a href="./../methods" class="span4 well panel-main-menu panel-main-menu-col2" id="panel-mm-methods">
                <img src="./library/img/chemistry-icon.png" class="img-rounded panel-icon"> 
                <span class="text-main-menu">Method Lab</span>
        </a>
    </div>
</div>  
<style>
    body{
        background-image: url('./library/img/back2.jpg');
        background-size: 120%;
        background-position-y: -120px;
    }
    input[type="text"]:focus,
    input[type="password"]:focus,
    .btn:focus{
        border-color:  rgba(255, 255, 255, 0.6);
        outline: 0;
        outline: thin dotted \9;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px  rgba(255, 255, 255, 0.6);
        -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px  rgba(255, 255, 255, 0.6);
        box-shadow: inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(255, 255, 255, 0.6);
    }
    
     .n-form-login{
        width: 230px;
        height: 260px;
        margin: 10px;
        margin-left: auto; 
        margin-right: auto; 
        -webkit-border-radius: 5px;
        -moz-border-radius: 4px;
        border-radius: 5px;
        border: 1px solid rgba(41, 41, 41, 0.48);
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
        -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
        box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
        /* background-color: rgba(141, 141, 141, 0.58); */
        box-shadow: 1px 4px 10px rgba(2, 2, 2, 0.61);
        background: rgb(239, 239, 239);
        background: -moz-linear-gradient(21deg, rgba(15, 15, 15, 0.3) 0%, rgba(202, 202, 202, 0.33) 100%);
        background: -webkit-linear-gradient(21deg, rgba(15, 15, 15, 0.3) 0%, rgba(202, 202, 202, 0.33) 100%);
        background: -o-linear-gradient(21deg, rgba(15, 15, 15, 0.3) 0%, rgba(202, 202, 202, 0.33) 100%);
        background: -ms-linear-gradient(21deg, rgba(15, 15, 15, 0.3) 0%, rgba(202, 202, 202, 0.33) 100%);
        background: linear-gradient(21deg, rgba(15, 15, 15, 0.3) 0%, rgba(202, 202, 202, 0.33) 100%);
    }
    .n-form-header-login{
        padding-top: 10px;
        /* background-color: rgba(56, 56, 56, 0.31); */
        border-top-left-radius: 4px;
        -webkit-border-top-left-radius: 4px;
        border-top-right-radius: 4px;
        -webkit-border-top-right-radius: 4px;
        border-bottom: 1px solid rgba(73, 73, 73, 0.86);
        /* border-top: 1px solid rgba(73, 73, 73, 0.89); */
        height: 26px;
        -webkit-box-shadow: inset 0px 1px 1px rgba(255, 255, 255, 0.44);
        -moz-box-shadow: inset 0px 1px 1px rgba(255, 255, 255, 0.44);
        box-shadow: inset 0px 1px 1px rgba(255, 255, 255, 0.44);
        color: rgba(238, 238, 238, 0.94);
        font-weight: bold;
        font-size: 13px;
        background: rgb(99, 99, 99);
        background: -moz-linear-gradient(0deg, rgba(99, 99, 99, 0.64) 0%, rgba(116, 116, 116, 0.49) 100%); 
        background: -webkit-linear-gradient(0deg, rgba(99, 99, 99, 0.64) 0%, rgba(116, 116, 116, 0.49) 100%); 
        background: -o-linear-gradient(0deg, rgba(99, 99, 99, 0.64) 0%, rgba(116, 116, 116, 0.49) 100%); 
        background: -ms-linear-gradient(0deg, rgba(99, 99, 99, 0.64) 0%, rgba(116, 116, 116, 0.49) 100%); 
        background: linear-gradient(0deg, rgba(99, 99, 99, 0.64) 0%, rgba(116, 116, 116, 0.49) 100%);  
    }
    .n-form-title-login{
        margin-left: auto;
        margin-right: auto;
        width: 55px;
        display: block;
        text-shadow: 1px 1px 2px #646464;
    }
    .n-form-body-login{
        padding: 5px;
        padding-top: 10px;
        background-color: rgba(0, 0, 0, 0.07);
        border-bottom-left-radius: 4px;
        -webkit-border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        -webkit-border-bottom-right-radius: 4px;
        /*border: 1px solid rgba(250, 250, 250, 0.2);*/
        /* border-bottom: 1px solid rgba(90, 90, 90, 0.86); */
        height: 213px;
         -webkit-box-shadow: inset 0px 1px 1px rgba(255, 255, 255, 0.32);
        -moz-box-shadow: inset 0px 1px 1px rgba(255, 255, 255, 0.32);
         box-shadow: inset 0px 1px 1px rgba(255, 255, 255, 0.32);
        color: rgba(238, 238, 238, 0.94);
        font-weight: bold;
        font-size: 14px; 
    }
    .icon-input{
        font-size: 20px;
        position: absolute;
        margin: 7px;
        opacity: 0.8;
    }
    .n-input-login{
        background: transparent;
        border: none;
        color: #DBDADA;
        height: 25px;
        margin: 0px;
        width: 153px;
        height: 26px;
        text-align: center;
        font-family: cursive;
    }
    .container-input{
        background-color: rgba(68, 68, 68, 0.08);
        border-radius: 5px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        box-shadow: inset -1px 1px 5px rgba(44, 44, 44, 0.77);
        height: 34px;
        width: 165px;
        margin-top: 10px;
    }
    #n_containerLogIn{
        margin-top: 10%/*15%*/;
        margin-right: auto;/*20%*/;
    }
    .panel-icon{
        max-width: 124px;
        margin-right: 10px;
    }
    #back-video{
       width: 1604px;
        height: 903px;
        z-index: -999;
        position: fixed;
        top: 0px;
        left: 0px;
        
    }
    @media (max-width: 768px) {
        body{
            /*background: rgba(32,124,229,1);
            background: -moz-linear-gradient(top, rgba(32,124,229,1) 0%, rgba(100,150,196,1) 52%, rgba(118,150,207,1) 100%);
            background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(32,124,229,1)), color-stop(52%, rgba(100,150,196,1)), color-stop(100%, rgba(118,150,207,1)));
            background: -webkit-linear-gradient(top, rgba(32,124,229,1) 0%, rgba(100,150,196,1) 52%, rgba(118,150,207,1) 100%);
            background: -o-linear-gradient(top, rgba(32,124,229,1) 0%, rgba(100,150,196,1) 52%, rgba(118,150,207,1) 100%);
            background: -ms-linear-gradient(top, rgba(32,124,229,1) 0%, rgba(100,150,196,1) 52%, rgba(118,150,207,1) 100%);
            background: linear-gradient(to bottom, rgba(32,124,229,1) 0%, rgba(100,150,196,1) 52%, rgba(118,150,207,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#207ce5', endColorstr='#7696cf', GradientType=0 );*/
            background-image: url('./library/img/back2.jpg');
            background-size: 400%;
            background-position-y: -200px;
        }
        .title-container-login{
            width: 210px;
            margin-left: auto;
            margin-right: auto;
        }
        .panel-main-menu-col1 {
            margin-left: auto;
        }
        #title-login{
         text-shadow: 2px 2px rgb(65, 65, 68);
        }
    }
    
</style>
<script >
    
     
    $("#"+  <?php echo ($_SESSION["u_valid"]?"'n_containerLogIn'":"'body-container-mm-login'")?> ).remove();
    if (<?php echo (!$first_time && !$_SESSION["u_valid"] ?"true":"false")?>){
         ArataMesajAlerta("Atention!<br/>",
                                                "Username or Password are invalid.",
                                                "danger",
                                                "",
                                                false,
                                                3000);
    }
</script>
<?php
include dirname(__FILE__).'/include/footer.php';
?>