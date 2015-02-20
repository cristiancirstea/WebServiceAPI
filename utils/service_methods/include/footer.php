
   <footer class="container-fluid">
       <?php
     //--------anulat momentan--------
       $showCopyRight=false;
     //-------------------------------
        if (isset($showCopyRight))
        {
            if ($showCopyRight===true)
            echo '
       <div class="navbar  navbar-static-bottom navbar-inverse">         
        <div class="pull-right" id="MyCopyright">
            @Copyright
            <a href="#" target="_blank">Cristian Cocioaba</a>
        </div>
        </div>
            ';
        }
       ?>
    </footer>
<?php
    include_once dirname(__FILE__).'/IncludeFiles.php';

    foreach($arAdditionalScript as $index => $elem)
    {
        echo  '<script src="'.$elem.'" type="text/javascript"></script> ';
    }
?>


