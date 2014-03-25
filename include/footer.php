<!--body-container-->
<!--    </div>  -->
   <footer class="container-fluid">
       <?php
     //--------anulat momentan--------
       $showCopyRight=true;
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
</body>
</html>

