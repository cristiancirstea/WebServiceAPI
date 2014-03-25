
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
});


function GetBtnURI(theBtn)
{
if (typeof(theBtn)==="string")
        var theBtn=$('#'+theBtn);
    else
        var theBtn=theBtn;
	var URI=theBtn.parent(".btn-group-ws-response")
					.siblings("input").val();
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
		Console(URI);
		return URI;
}

function GetBtnRequest(theBtn)
{
if (typeof(theBtn)==="string")
        var theBtn=$('#'+theBtn);
    else
        var theBtn=theBtn;
	var methodNr=theBtn.attr("methodNr");
	var extraRequest=$("#extra-request"+methodNr).val();
		extraRequest=extraRequest.trim();
		if (extraRequest=='')
			{
				extraRequest='{}';
			}
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