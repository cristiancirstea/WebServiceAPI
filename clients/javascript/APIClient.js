//TODO add support for default browser request
/**
 *
 * @param strAPIRootURL
 * @returns {*}
 * @constructor
 */
function APIClient(strAPIRootURL)
{
    if (!this.checkAjax())
        return null;
    if (typeof strAPIRootURL !== "undefined")
    {
        this.API_ROOT_URL = strAPIRootURL;
    }
    return this;
}

/**
 * Root URL for api server. (Api endpoint).
 * @type {string}
 */
APIClient.prototype.API_ROOT_URL = "http://APIhost/APIFolder/api";

APIClient.prototype._objRequestsOptions = {
    params : {},
    method : "GET",
    callBack : undefined,
    callBackExtraParams : undefined,
    async : true,
    logParams : false,
    logResponse : false,
    //timeReponse : false,
    useDefErrorHandler : true,
    parseResponse : function(strResponse){
        return strResponse;
    },
    encodeRequest : function(mxRequestParams){
        if ( typeof(mxRequestParams) == "string")
            return this.jsonSafeParse(mxRequestParams, {});
        else
            return mxRequestParams;
    },
    errorDefHandler : function(jqXHR, textStatus){
        console.error("Request failed!");
        console.log(
            {
                url : strURL,
                params : objRequestOptions.params,
                response : jqXHR,
                textStatus : textStatus
            }
        );
    },
    errorHandler : function(jqXHR, textStatus){
        return;
    }
};

APIClient.prototype.jsonSafeParse = function(strText, defValue)
{
    try{
        return JSON.parse(strText);
    }
    catch(er)
    {
        console.error("JSON parse error\n\r" + er + "\n\rText: \"" + strText + "\"");
        if (defValue)
            return defValue;
        else
            return null;
    }
}

APIClient.prototype.checkAjax = function()
{
    if ($.ajax)
        return true;
    throw new Error("Ajax support not found.");
}

APIClient.prototype.getRequestsOption = function(strOptionName)
{
    return this._objRequestsOptions[strOptionName];
}

APIClient.prototype.setRequestsOption = function(strOptionName, mxValue)
{
    this._objRequestsOptions[strOptionName] = mxValue;
}

/**
 * Custom ajax request/call to API server.
 * Sync request is deprecated and not supported in some browsers.
 * @param strURL
 * @param objRequestOptions
 * @returns {undefined|mixed} mixed for sync request
 * @private
 */
APIClient.prototype._call = function(strURL, objRequestOptions)
{
    var result = undefined;
    if (typeof objRequestOptions === "undefined")
        var objRequestOptions = {};
    var thisInstance = this;
    if (typeof this.API_ROOT_URL === "undefined")
        throw new Error("Undefined API_ROOT_URL");
    strURL = this.API_ROOT_URL + "/" + strURL;

    objRequestOptions = $.extend(true, this._objRequestsOptions, objRequestOptions);
    console.log(objRequestOptions);

    if (objRequestOptions.encodeRequest)
        objRequestOptions.params = objRequestOptions.encodeRequest(objRequestOptions.params);

    if (objRequestOptions.logParams)
        console.log("Params ->",objRequestOptions.params);

    var result = null;

    //request start
    var request = $.ajax({
        url: strURL,
        type: objRequestOptions.method,
        async: objRequestOptions.async,
        data: objRequestOptions.params,
        contentType: 'application/json; charset=UTF-8',
        dataType: 'json',
        crossDomain : true
    });

    //request done
    request.done(
        function(response) {
            if (objRequestOptions.logResponse)
                thisInstance.console("Response -> ", response);

            if (!objRequestOptions.async)
                result = objRequestOptions.parseResponse(response);

            if (objRequestOptions.callBack)
            {
                var callbacks = $.Callbacks();
                callbacks.add( objRequestOptions.callBack );
                if ( objRequestOptions.callBackExtraParams )
                    callbacks.fire(response, objRequestOptions.callBackExtraParams);
                else
                    callbacks.fire(response);
            }
        }
    );

    //request error
    request.fail(function(jqXHR, textStatus) {
        if (objRequestOptions.useDefErrorHandler)
            objRequestOptions.errorDefHandler(jqXHR, textStatus);
        objRequestOptions.errorHandler(jqXHR, textStatus);
    });
    return result;
};

