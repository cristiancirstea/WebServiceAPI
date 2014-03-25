<?php
include './include/header.php';
include 'My_API.php';
?>
 <div class="container well">
	<div class="pull-left container span6">
		<?php
			$WS_ROOT='http://localhost:1234/WS/service';
			$URI="/";
			$rClass = new ReflectionClass('MyAPI');
			$array = NULL;
			$methodNr=0;
			$paramNr=0;
			foreach ($rClass->getMethods(ReflectionMethod::IS_PROTECTED) as $method)
			{
				$URI="/".$method->name;
				echo '<div class="row-method" id="row-method'.$methodNr.'"> '
						.'<div class="method-name">'
							.'<span class="text-method">'.$method->name.'</span>'
							.'<button class="btn btn-toggle-params pull-right">'
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
									echo
										'<div class="method-param-name">'
											.'<label for="param-'.$params->name.$paramNr.'" class="label-param-name">'
													.$params->name.':'.
											'</label>';
									if ($countParameters===1)
									{
										echo '<div class="input-append ia-method-param-value">'
												.'<input type="text" id="param-'.$params->name.$paramNr.'" 
												class="method-param-value method-param-value'.$methodNr.'"/>'
												  .'<button class="btn btn-small  btn-add-param-value" title="Add Value" 
														type="button">+</button>'
											.'</div>';
									}
									else
									{
										echo '<input type="text" id="param-'.$params->name.$paramNr.'" 
												class="method-param-value method-param-value'.$methodNr.'"/>';
									}
									echo '</div> ';
										
								echo '</div>';
								$paramNr++;
							}
						}
						//extra request TODO
						echo '<div class="method-extra-request" id="method-extra-request'.$methodNr.'">'
								.'<label for="extra-request'.$methodNr.'" class="label-extra-request">'
											.'Request:'
											.'</label>'
											.'<textarea  id="extra-request'.$methodNr.'" 
												class="extra-request extra-request'.$methodNr.'"></textarea>'
							.'</div>';
						//buttons
						echo '<div class="method-buttons" id="method-buttons'.$methodNr.'" >'
								.'<input type="hidden" id="uri-method'.$methodNr.'" 
										value="'.$WS_ROOT.$URI.'">'
								.'<div class="btn-group btn-group-ws-response pull-right">'
									.'<button class="btn btn-small btn-ws-response btn-get-response"
										id="btn-get-response'.$methodNr.'" methodNr="'.$methodNr.'">
										Get
									</button>'
									.'<button class="btn btn-small btn-ws-response btn-post-response"
										id="btn-post-response'.$methodNr.'" methodNr="'.$methodNr.'">
										Post
									</button>'
									.'<button class="btn btn-small btn-ws-response btn-put-response"
										id="btn-put-response'.$methodNr.'" methodNr="'.$methodNr.'">
										Put
									</button>'
									.'<button class="btn btn-small btn-ws-response btn-delete-response"
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
	<div class="container span4">
		<label id="label-return-ws" for="text-return-ws" > <b>Return: </b></label>
		<textarea class="span5" id="text-return-ws"></textarea>
	</div>
</div>


<?php
include './include/footer.php';
?>
