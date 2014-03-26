
$(document).on("ready",function(){
		$(".method-options").hide();
		//toggle params list
		$(".btn-toggle-params").on("click",function(){
			var btn=$(this);
			var paramsToShow=$(this).parent(".method-name")
					.siblings(".method-options");
			paramsToShow.slideToggle(50,function(){
						if (ElementVisible($(paramsToShow)))
							{
								
								btn.text('-');
							}
							else{
								btn.text('+');
							}
					});
			
		});
		
	ActivateBtnGetResponse();	
	ActivateBtnPostResponse();
	ActivateBtnPutResponse();
	ActivateBtnDeleteResponse();
	
	ActivateBtnAddParamValue();
	ActivateBtnAddExtraRequest();
	ActivateBtnCloseParent();
});


function GetBtnURI(theBtn)
{
if (typeof(theBtn)==="string")
        var theBtn=$('#'+theBtn);
    else
        var theBtn=theBtn;
	var URI=theBtn.parent(".btn-group-ws-response")
					.siblings(".inputMethodNr").val();
		//Console(URI);
		var methodNr=theBtn.attr("methodNr");
		//Console(methodNr);
		$(".method-param-value"+methodNr).each(function(){
			var paramVal=$(this).val();
			paramVal=paramVal.trim();
			var paramName=$(this).siblings('label').text();
			paramName=paramName.trim();
			paramName=paramName.replace(':','');
			if (paramVal!='')
			{
				URI+='/'+paramVal;
			}
			//Console(paramName+'->'+paramVal);
		});
		//Console(URI);
		return URI;
}

function GetBtnRequest(theBtn)
{
if (typeof(theBtn)==="string")
        var theBtn=$('#'+theBtn);
    else
        var theBtn=theBtn;
	var methodNr=theBtn.attr("methodNr");
	var arParamName=[];
	var arParamValue=[];
	var paramName='';
	var paramValue='';
	$("#method-extra-request"+methodNr).children('.container-extra-request')
	.each(function(){
		paramName=$(this).children('.container-extra-request-name')
			.children('input').val();
		paramValue=$(this).children('.container-extra-request-value')
			.children('input').val();
		if (typeof(paramName)!='undefined')
			{
				paramName=paramName.trim();
				if (paramName!='')
				{
					arParamName[arParamName.length]=paramName;
					arParamValue[arParamValue.length]=paramValue;
				}
			}
	});
	var extraRequest=GenerateJSONString(arParamName,arParamValue);
		//Console(extraRequest);
		return extraRequest;
}

function ActivateBtnGetResponse()
{
	$(".btn-get-response").on("click",function(){
		
		GetDataFromRequest(GetBtnURI($(this)),GetBtnRequest($(this)),
			"GET",function(obj){
					Console(obj);
					$("#text-return-ws").val(JSON.stringify(obj));
				})
	});
}
function ActivateBtnPostResponse()
{
	$(".btn-post-response").on("click",function(){
		
		GetDataFromRequest(GetBtnURI($(this)),GetBtnRequest($(this)),
			"POST",function(obj){
					Console(obj);
					$("#text-return-ws").val(JSON.stringify(obj));
				})
	});
}
//-----------------------PROBLEM!!!!!!!!!!!!!!!!!!!!!!!!!!!
function ActivateBtnPutResponse()
{
	$(".btn-put-response").on("click",function(){
		
		GetDataFromRequest(GetBtnURI($(this)),GetBtnRequest($(this)),
			"PUT",function(obj){
					Console(obj);
					$("#text-return-ws").val(JSON.stringify(obj));
				})
	});
}
function ActivateBtnDeleteResponse()
{
	$(".btn-delete-response").on("click",function(){
		
		GetDataFromRequest(GetBtnURI($(this)),GetBtnRequest($(this)),
			"DELETE",function(obj){
					Console(obj);
					$("#text-return-ws").val(JSON.stringify(obj));
				})
	});
}
//-------------------------------------

function ActivateBtnAddParamValue()
{
	$(".btn-add-param-value").on("click",function(){
		var inputHtml=$(this).siblings("input").outerHTML();
		var removeInputHtml='<button type="button" class="close close-parent close-input-param-value" '
								+'id="'+AvailableID('close-input-param-value')+'">'
								+'&times;'
								+'</button>';
		var parinte=$(this).parent(".ia-method-param-value");
		parinte.append('<div class="container-extra-param" '
						+'id="'
						+AvailableID("container-extra-param")+
						'">'
							+inputHtml
							+removeInputHtml
						+'</div>');
		ActivateBtnCloseParent();	
	});
}
function ActivateBtnAddExtraRequest()
{
	$(".btn-add-extra-request").on("click",function(){
		var inputHtml=$(this).siblings("input").outerHTML();
		var removeInputHtml='<button type="button" class="close close-parent close-input-param-value" '
								+'id="'+AvailableID('close-input-param-value')+'">'
								+'&times;'
								+'</button>';
		var parinte=$(this).parent(".container-extra-request").parent();
		var containerExtraRequestHtml='<div class="well container-extra-request" '
												+'id="'+AvailableID('container-extra-request')+'">'
												+'<div class="container-extra-request-name">'
													+'<label for="'+AvailableID('extra-request-param-name')+'">Name:</label>'
													+'<input type="text" id="'+AvailableID('extra-request-param-name')+'"/>'
												+'</div>'
												+'<div class="container-extra-request-value">'
													+'<label for="'+AvailableID('extra-request-param-value')+'">Value:</label>'
													+'<input type="text" id="'+AvailableID('extra-request-param-value')+'"/>'
												+'</div>'
												+'<button type="button" class="close close-parent">&times;</button>'
										+'</div>';
		parinte.append(containerExtraRequestHtml);
		ActivateBtnCloseParent();	
	});
}
function ActivateBtnCloseParent(){
	$(".close-parent").on("click",function(){
		$(this).parent("div").remove();
	});
}
function ResetAllInputs()
{
	$("input").val("");
	$("textarea").val("");
}



