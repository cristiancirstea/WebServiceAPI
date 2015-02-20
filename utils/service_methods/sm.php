<?php
include  '/include/header.php';
include '/../../servers/php/APIServer.php';
$CLASS_NAME = "APIServer";
//$WS_URL = "./../servers/php/api/methods";
$WS_ROOT = "http://localhost/WebServiceAPI/servers/php/api/";
$WS_METHODS_URI = "methods";
?>
 <div class="container well" id="main-container">
     <div class=" span6">
         <a class="" href="Logs/log" id="link-log">Log</a>
         <a class="" href="Logs/error" id="link-erlog"> Error Log</a>
     </div>
         <div class="pull-left container span6" id="methods-container">

		</div>
	<div class="container span5 " id="container-response">
		<button type="button" class="btn pull-right" id="resetAllInputs"
			onclick="ResetAllInputs();">
				Reset All
		</button>
		<div class="">
			<span> Response status : </span>
			<span id="response-status" class="text-info">-</span>
			<label id="label-return-ws" for="text-return-ws" >
				<b>
					Return:
				</b>
			</label>
			<textarea class="span5" id="text-return-ws"></textarea>
		</div>
	</div>
</div>
<style >
     @media (min-width: 768px) {
        #container-response{
            position: fixed;
            top: 10px;
            left: 52%;
        }
         
     }
	#response-status{
		font-weight: bold;
	}
</style>
<script>
	var API = {};
	var _WS_ROOT = "<?php echo $WS_ROOT; ?>";
</script>
<?php
	include dirname(__FILE__).'/include/footer.php';
?>
<script>

	getAPIMethods();
</script>
</body>
</html>