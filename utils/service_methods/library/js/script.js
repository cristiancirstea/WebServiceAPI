

function getAPIMethods()
{
	getAPIResponse("methods",
		[],
		"GET",
		renderAPIMethods
	);
}

function renderAPIMethods(arrMethods)
{
	if (typeof arrMethods === "undefined")
		return;
	var containerMethods = document.getElementById("methods-container");
	for(var i = 0; i < arrMethods.length; i++)
	{
		var strHTMLParams = "<div class=\"method-params\">";
		if (arrMethods[i].params)
			for (var j = 0; j < arrMethods[i].params.length; j++)
			{
				strHTMLParams += "<div class=\"method-param-name\">";
				strHTMLParams += "<label for=\"param-" + arrMethods[i].params[j].name + j + "\" class=\"label-param-name\">";
				strHTMLParams += arrMethods[i].params[j].name + " : </label>";
				strHTMLParams += "<input type=\"text\" id=\"param-" + arrMethods[i].params[j].name + j + "\"";
				if (arrMethods[i].params[j].default)
					strHTMLParams += " placeholder=\"" + arrMethods[i].params[j].default + "\" ";
				strHTMLParams += "class=\"method-param-value method-param-value" + i +"\"/>";
				strHTMLParams += "</div> ";
			}
		strHTMLParams += "</div>";
		var strHTMLActions =
			"<div class=\"method-buttons\" id=\"method-buttons" + i + "\" >" +
			" <input type=\"hidden\" class=\"inputMethodNr\" id=\"uri-method" + i + "\"" +
			" value=\"" +  arrMethods[i].name + "\">" +
			" <div class=\"btn-group btn-group-ws-response pull-right\">" +
			" <button class=\"btn btn-small  btn-ws-response btn-get-response\"" +
			" id=\"btn-get-response" + i + "\" methodNr=\"" + i + "\">" +
			" <b>Get</b>" +
			" </button>" +
			" <button class=\"btn btn-small  btn-ws-response btn-post-response\" " +
			" id=\"btn-post-response" + i + "\" methodNr=\"" + i + "\"> " +
			" Post" +
			" </button>" +
			" <button class=\"btn btn-small btn-ws-response btn-put-response\" " +
			" id=\"btn-put-response" + i + "\" methodNr=\"" + i + "\">" +
			" Put " +
			" </button> " +
			" <button class=\"btn btn-small btn-danger btn-ws-response btn-delete-response\"" +
			" id=\"btn-delete-response" + i + "\" methodNr=\" + i + \">" +
			" Delete " +
			" </button>"+
			" </div>" +
			" </div>";
		var strHTML =
			"<div class=\"row-method\" id=\"row-method" + i + "\">" +
			"<div class=\"method-name\">" +
			"<span class=\"text-method cursor-pointer\" " +
			"onclick=\"$('#mth-btn" + i +"').trigger('click')\" id=\"mth-name" + i + "\">" +
			arrMethods[i].name +
			"</span>" +
			"<button class=\"btn btn-toggle-params pull-right\" id=\"mth-btn" + i + "\">" +
			"+" +
			"</button>" +
			"</div>" +
			"<div class=\"method-options\" id=\"method-options" + i +"\">" +
			strHTMLParams +
			strHTMLActions +
			"</div>" +
			"</div>";
		containerMethods.innerHTML += strHTML + "\n";

	}
	pageInit();
	$("textarea").val("");
}

function checkUtils()
{
	if (typeof(utils) === 'undefined')
	{
		try
		{
			var utils = new Utils();
		}
		catch(exc)
		{
			console.error(exc);
			alert("Utils library not found!");
			return false;
		}
	}
	return true;
}

function renderAPIError(strErrorMessage, nErrorCode)
{
	console.error(strErrorMessage, nErrorCode);
}

function renderAPIResponse(mxResponse)
{
	if (typeof mxResponse === "undefined")
	{
		throw new Error("Undefined response.");
	}

	var textarea = document.getElementById("text-return-ws");
	var statusCode = document.getElementById("response-status");

	textarea.value = JSON.stringify(mxResponse, undefined, 4);
	statusCode.innerHTML = mxResponse.status;
	if (mxResponse.status)
	{
		if (mxResponse.status >= 200 && mxResponse.status < 300)
			statusCode.className = "text-success";
		else
			statusCode.className = "text-danger";
	}
	//TODO use ace9 editor
	//console.log(mxResponse);
}


function getAPIResponse(strMethodName, arrParams, strHTTPRequestType, onResponse, onError)
{
	if (!checkUtils()) return;
	if (typeof strHTTPRequestType === "undefined")
	{
		strHTTPRequestType = "GET";
	}
	if (typeof arrParams === "undefined")
	{
		arrParams = [];
	}
	utils.getDataFromRequest(
		_WS_ROOT + strMethodName,
		{
			method : strHTTPRequestType,
			params : arrParams,
			callBack : function(mxResult)
			{
				if (mxResult instanceof Error)
					throw mxResult;
				if (mxResult.error)
				{
					if (onError)
					{
						onError(new Error(mxResult.error, mxResult.code));
					}
					var error =  new Error(mxResult.error, mxResult.code);
					renderAPIError(mxResult.error, mxResult.code);
					//throw error;
				}

				renderAPIResponse(mxResult);
				if (onResponse)
				{
					onResponse(mxResult.result);
				}

			}
		}
	);
}

function pageInit()
{
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

	if (ElementVisible("body-container-editor")){
		$("#body-container-editor").css({
			height:(window.innerHeight-80)+"px"
		});
	}

}

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

		getAPIResponse(GetBtnURI($(this)),[],"GET");
		//GetDataFromRequest(GetBtnURI($(this)),GetBtnRequest($(this)),
		//	"GET",function(obj){
		//			//Console(obj);
		//			$("#text-return-ws").val(JSON.stringify(obj,undefined,4));
		//		});
	});
}
function ActivateBtnPostResponse()
{
	$(".btn-post-response").on("click",function(){

		getAPIResponse(GetBtnURI($(this)),[],"POST");
		//GetDataFromRequest(GetBtnURI($(this)),GetBtnRequest($(this)),
		//	"POST",function(obj){
		//			Console(obj);
		//			$("#text-return-ws").val(JSON.stringify(obj,undefined,4));
		//		})
	});
}
//-----------------------PROBLEM!!!!!!!!!!!!!!!!!!!!!!!!!!!
function ActivateBtnPutResponse()
{
	$(".btn-put-response").on("click",function(){
		getAPIResponse(GetBtnURI($(this)),[],"PUT");
		//GetDataFromRequest(GetBtnURI($(this)),GetBtnRequest($(this)),
		//	"PUT",function(obj){
		//			Console(obj);
		//			$("#text-return-ws").val(JSON.stringify(obj,undefined,4));
		//		})
	});
}
function ActivateBtnDeleteResponse()
{
	$(".btn-delete-response").on("click",function(){

		getAPIResponse(GetBtnURI($(this)),[],"DELETE");
		//GetDataFromRequest(GetBtnURI($(this)),GetBtnRequest($(this)),
		//	"DELETE",function(obj){
		//			Console(obj);
		//			$("#text-return-ws").val(JSON.stringify(obj,undefined,4));
		//		})
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
	$("input[type!='hidden']").val("");
	$("textarea").val("");
}



