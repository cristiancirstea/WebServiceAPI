<?php
include  '/include/header.php';
include '/../../servers/php/APIServer.php';
$CLASS_NAME = "APIServer";
?>
 <div class="container well" id="main-container">
     <div class=" span6">
         <a class="" href="Logs/log" id="link-log">Log</a>
         <a class="" href="Logs/error" id="link-erlog"> Error Log</a>
     </div>
         <div class="pull-left container span6">
		<?php
			function startsWith($haystack, $needle)
			{
				return $needle === "" || strpos($haystack, $needle) === 0;
			}
			function endsWith($haystack, $needle)
			{
				return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
			}
			$URI_FOR_REWRITE_RULE='methods';
			$fullURL="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			if (endsWith($fullURL,$URI_FOR_REWRITE_RULE)){
				$strToReplace=$URI_FOR_REWRITE_RULE;
			}
			else{
				$strToReplace=basename($_SERVER['PHP_SELF']);
			}
			$WS_ROOT= str_replace($strToReplace,'service',$fullURL);
			$URI="/";
			$rClass = new ReflectionClass($CLASS_NAME);
			$array = NULL;
			$methodNr=0;
			$paramNr=0;
			foreach ($rClass->getMethods(ReflectionMethod::IS_PROTECTED) as $method)
			{
				$URI="/".$method->name;
				echo '<div class="row-method" id="row-method'.$methodNr.'"> '
						.'<div class="method-name">'
							.'<span class="text-method cursor-pointer" ' 
                                                            .'onclick="'."$('#mth-btn$methodNr').trigger('click')".'" id="mth-name'.$methodNr.'">'
                                                            .$method->name
                                                        .'</span>'
							.'<button class="btn btn-toggle-params pull-right" id="mth-btn'.$methodNr.'">'
								.'+'
							.'</button>'
						.'</div>';
					echo '<div class="method-options" id="method-options'.$methodNr.'">';
					$countParameters=count($method->getParameters());
						//params
						if ($countParameters===0)
						{
							echo '';
						}
						else
						{
							foreach ($method->getParameters() as $params)
							{
								echo '<div class="method-params">';
								//if method has 1 param it will accept array of params
									if ($countParameters===1)
									{
										echo
										'<div class="method-param-name">'
											.'<label for="param-'.$params->name.$paramNr.'" class="label-param-name">'
													.$params->name.':<br/>(array)'.
											'</label>';
									
										echo '<div class="input-append ia-method-param-value">'
												.'<input type="text" id="param-'.$params->name.$paramNr.'" 
												class="method-param-value method-param-value'.$methodNr.'"/>'
												  .'<button class="btn btn-small  btn-add-param-value" title="Add Value" 
														type="button">Add</button>'
											.'</div>';
									}
								//else fixed number of params
									else
									{
										echo
										'<div class="method-param-name">'
											.'<label for="param-'.$params->name.$paramNr.'" class="label-param-name">'
													.$params->name.':'.
											'</label>';
										echo '<input type="text" id="param-'.$params->name.$paramNr.'" 
												class="method-param-value method-param-value'.$methodNr.'"/>';
									}
									echo '</div> ';
										
								echo '</div>';
								$paramNr++;
							}
						}
						//extra request -> it will be automatically to JSON and sent as request
						echo '<div class="method-extra-request" id="method-extra-request'.$methodNr.'">'
								.'<label for="extra-request'.$methodNr.'" class="label-extra-request">'
											.'Request Params:'
											.'</label>'
											//.'<textarea  id="extra-request'.$methodNr.'" 
											//	class="extra-request extra-request'.$methodNr.'"></textarea>'
											.'<div class="well container-extra-request" id="container-extra-request">'
												.'<div class="container-extra-request-name">'
													.'<label for="extra-request-param-name">Name:</label>'
													.'<input type="text" id="extra-request-param-name"/>'
												.'</div>'
												.'<div class="container-extra-request-value">'
													.'<label for="extra-request-param-value">Value:</label>'
													.'<input type="text" id="extra-request-param-value"/>'
												.'</div>'
												//.'<button type="button" class="close close-parent">&times;</button>'
												.'<button class="btn pull-right btn-small btn-add-extra-request " title="Add Request Params" 
														type="button">Add</button>'
											.'</div>'
							.'</div>';
						//buttons for HTTP method of request
						echo '<div class="method-buttons" id="method-buttons'.$methodNr.'" >'
								.'<input type="hidden" class="inputMethodNr" id="uri-method'.$methodNr.'" 
										value="'.$WS_ROOT.$URI.'">'
								.'<div class="btn-group btn-group-ws-response pull-right">'
									.'<button class="btn btn-small  btn-ws-response btn-get-response"
										id="btn-get-response'.$methodNr.'" methodNr="'.$methodNr.'">
										<b>Get</b>
									</button>'
									.'<button class="btn btn-small  btn-ws-response btn-post-response"
										id="btn-post-response'.$methodNr.'" methodNr="'.$methodNr.'">
										Post
									</button>'
									.'<button class="btn btn-small btn-ws-response btn-put-response"
										id="btn-put-response'.$methodNr.'" methodNr="'.$methodNr.'">
										Put
									</button>'
									.'<button class="btn btn-small btn-danger btn-ws-response btn-delete-response"
										id="btn-delete-response'.$methodNr.'" methodNr="'.$methodNr.'">
										Delete
									</button>'
								.'</div>' 
							.'</div>';
					echo '</div>';
				echo "</div>";
				$methodNr++;
			}
		?>
	</div>
	<div class="container span5 " id="container-response">
	<button type="button" class="btn pull-right" id="resetAllInputs" 
		onclick="ResetAllInputs();">
			Reset All
	</button>
		<div class="">
			<label id="label-return-ws" for="text-return-ws" > <b>Return: </b></label>
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
</style>
<?php
include dirname(__FILE__).'/include/footer.php';
?>
