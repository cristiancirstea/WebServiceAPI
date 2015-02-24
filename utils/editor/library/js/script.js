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
	
	ActivateContextualMenu("a.file,a.picture,a.folder");
        
        if (ElementVisible("body-container-editor")){
            $("#body-container-editor").css({
                height:(window.innerHeight-80)+"px"
            });
        }
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
					$("#text-return-ws").val(JSON.stringify(obj,undefined,4));
				})
	});
}
function ActivateBtnPostResponse()
{
	$(".btn-post-response").on("click",function(){
		
		GetDataFromRequest(GetBtnURI($(this)),GetBtnRequest($(this)),
			"POST",function(obj){
					Console(obj);
					$("#text-return-ws").val(JSON.stringify(obj,undefined,4));
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
					$("#text-return-ws").val(JSON.stringify(obj,undefined,4));
				})
	});
}
function ActivateBtnDeleteResponse()
{
	$(".btn-delete-response").on("click",function(){
		
		GetDataFromRequest(GetBtnURI($(this)),GetBtnRequest($(this)),
			"DELETE",function(obj){
					Console(obj);
					$("#text-return-ws").val(JSON.stringify(obj,undefined,4));
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
	$("input[type!='hidden']").val("");
	$("textarea").val("");
}

function ActivateContextualMenu(selector)
{
    $.contextMenu({
        selector: "" + selector,
        //show: function(opt){ this.addClass('currently-showing-menu'); alert("Selector: " + opt.selector); },
        build: function($trigger, e){
        return {
            callback: function(){},
            items: {
                rename: {
                    name    : "Rename",
                    className   : "cm-item-rename",
                    callback: function(key, opt){
                            alert("rename is on the 'TODO' list!");
                        } 
                    },
                hide: {
                    name        : "Hide",
                    className   : "cm-item-hide",
                    callback    : function(key, opt){
                            alert("hide is on the 'TODO' list!");
                        } 
                },
                delete: {
                    name        : "Delete",
                    className   : "cm-item-delete",
                    callback    : function(key, opt){
                            var isDir = (typeof(opt.$trigger.attr("dir")) != "undefined");
                            var theName = isDir ? opt.$trigger.attr("dir") : opt.$trigger.attr("file");
                            var onOK = isDir ? ('deleteDir("' + theName + '")') : ('deleteFile("' + theName + '")');
                            ArataMesajAlerta('','Are you sure you want to delete ' +  (isDir ? 'directory' : "file")+'<br><b>"' + theName + '" </b>? <br><br>'
                                    + "<button class='btn btn-danger btn-delete-ok' onclick='" + onOK +";'>Yes</button> "
                                        + "<button class='btn btn-delete-no' onclick='$(this).parent().parent().parent().remove();'>No</button>"
                                    ,"warning"
                                    ,""
                                    ,false
                                    ,10000);
                            console.log(key,theName);
                        } 
                }
            }
        };
    }});
}
function deleteFile(fileName,stopReload){
                if (typeof(fileName) == "undefined")
                    return;
                return $.post("writer.php", 
                        {   
                            del_file : __USER_CONFIG__.editor_root_folder + fileName 
                        },
                        function() {
                                // add error checking
                               ArataMesajAlerta("Success!<br/>",
                                                "File deleted.",
                                                "success",
                                                "",//"navbar-editor",
                                                false,
                                                2000);
                            console.log("Deleted!" + fileName);
                            if (stopReload !== true)
                            setTimeout(function(){
                                    location.reload();
                                },1000);
                        }
                );
            };
function deleteDir(dirName,stopReload){
                if (typeof(dirName) == "undefined")
                    return;
                return $.post("writer.php", 
                        {   
                            del_dir : __USER_CONFIG__.editor_root_folder + dirName 
                        },
                        function() {
                                // add error checking
                               ArataMesajAlerta("Success!<br/>",
                                                "Directory deleted.",
                                                "success",
                                                "",//"navbar-editor",
                                                false,
                                                2000);
                            console.log("Deleted!" + dirName);
                            if (stopReload !== true)
                            setTimeout(function(){
                                    location.reload();
                                },1000);
                        }
                );
            };
