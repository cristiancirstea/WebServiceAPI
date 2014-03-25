var DEBUG=true;
function Console()
{
    if (DEBUG)
    {
            for(var i=0; i<arguments.length; i++) {
                console.log( arguments[i]);
            }
    }
}

var RunningFunction=[];
var _TEST_TIMER=0;
var _TEST_TIMER_RUNNING;
function TimerShow()
{
    console.log(_TEST_TIMER);
}
function TimerStop(){
    clearInterval(_TEST_TIMER_RUNNING);
    console.log(_TEST_TIMER);
    _TEST_TIMER=0;
};
function TimerStart(interv){
    if (typeof(interv)==="undefined")
        interv=1;
    _TEST_TIMER_RUNNING=setInterval(function(){
            _TEST_TIMER++;
        },interv);
};
function StopRF()
{
    for(var i=0;i<RunningFunction.length;i++)
        {
            clearInterval(RunningFunction[i]);
            RunningFunction[i]=null;
        }
}

// !!!!!----------------------- CEA MAI FOLOSITA FUNCTIE------------------!!!!!!!!!!!!
/**
 * <strong>Foarte Utila</strong><br>
 * Folosita la incarcarea unui PHP intr-un container (de obicei un div, dar nu conteaza care e...)
 * 
 * @param {String/Object} Container      id-ul Containerul/Container,
 *                                  parintele in care incarc pagina 
                                    !ATENTIE! suprascrie tot ce e in el <br>
                                    <b>Daca nu e dat id-ul suprasrie tot documentul.</b>
 * @param {String} numePagina       numele paginii pe care o incarc ex:"termene.php"
 * @param {String} params           parametrii care o sa fie transmisi prin GET sau POST
                      de forma :
                     <pre>   '{}' -> "nimic" = {} </pre>
                      <pre>  'true' pt bool = true </pre>
                      <pre>  '"foo"' pt string = "foo" </pre>
                      <pre>  '[1, 5, "false"]'  = [1, 5, "false"] </pre>
                      <pre>  '{"nume_param":"val_param",...}' cel mai util pt mai multi parametrii</pre>
                      <pre>  'null'  pt null </pre>
 * @param {String} submitMethod GET/POST
 * @param {bool} autoAfter true/false -> daca seteaza dupa incarcare inaltimea "auto" (cam in plus momentan)
 * @param {function} callBackFunction <strong>UTIL!</strong> pentru a executa cv dupa ce s-a incarcat pagina complet
 * @param {bool} append      Daca adaug la parinte ceea ce primesc sau suprasriu.
 */ 
function IncarcaPaginaPHP(Container,numePagina,params,submitMethod,autoAfter,callBackFunction,append)
{
    if (typeof(append)==="undefined" && append===null)
        append=false;
    if (typeof(Container)!=="undefined" && Container!==null)
       {
           ShowLoader(Container); 
       }
    if (typeof(Container)==="string")
        Container=$("#"+Container);
   if (typeof(autoAfter)==="undefined" && autoAfter===null)
        autoAfter=true; 
   submitMethod=submitMethod||"POST";
    var Jparams=JSON.parse(params);
//    console.log(Jparams);
    var request = $.ajax({
      url: numePagina,
      type: submitMethod,
      //headers: {Connection: close},
      data: Jparams//{id_pag : idPagina}
    });
 //cand s-a incarcat pagina...
    request.done(function(msg) {
        //console.log(msg);
    if (typeof(Container)!=="undefined" && Container!==null)
        {
            
            if (append)
                {
                   // Console(msg);
                   Container.append(msg) ;
                }
            else
              {  
                 // console.log(msg,"1uite");
                Container.html( msg ); 
              }
        }
      
  else
  {
      document.write(msg);
  }
//      console.log(msg);
      //daca s-a delogat din alt tab o sa primesc pagina de LogIn(index.php)
      // asa ca trebuie sa fac redirect catre LogIn(index.php) ca sa nu il pun doar in containerul cerut de functie 
//      if (ElementExits("MainLogInForm"))
//        window.location.assign("index.php");
      if (autoAfter)
        if (typeof(Container)!=="undefined" && Container!==null)  
           Container.css('height','auto'); 
    if (callBackFunction)
        {
            var callbacks = $.Callbacks();
            callbacks.add(callBackFunction);
            callbacks.fire();
        }
    });
//daca e vreo eroare...
    request.fail(function(jqXHR, textStatus) {
      alert( "Request failed: " + textStatus );
    });
}

function GetDataFromRequest(url,params,submitMethod,callBackFunction)
{
     TimerStart();
    submitMethod=submitMethod||"POST";
    var Jparams=JSON.parse(params);
    var theObj=null;
    //console.log(Jparams);
    var request = $.ajax({
      url: url,
      type: submitMethod,
      
      data: Jparams//{id_pag : idPagina}
    });
 //cand s-a incarcat pagina...
    request.done(function(msg) {
        //console.log(msg);
         TimerStop();
//          theObj=JSON.parse(msg);
//     console.log(theObj);
    if (callBackFunction)
        {
            var callbacks = $.Callbacks();
            callbacks.add(callBackFunction);
            callbacks.fire(JSONSafeParse(msg,true));
        }
    });
//daca e vreo eroare...
    request.fail(function(jqXHR, textStatus) {
		Console(jqXHR,textStatus);
      alert( "Request failed: " + textStatus );
    });
    
}
/**
  *<strong>Utila!</strong><br>
  *  Folosita pentru a genera stringul care sa fie transmis prin ajax in functia IncarcaPaginaPHP
  * @param {String []} arNumeParam vector cu numele parametrilor ex:["id_pag","NEW_SCHEDULE","NUME"]
  * @param {String []} arValParam vector cu valorile parametrilor ex:[1,true,"Vasile"]
  * @returns {String} De forma unui Obiect JSON <br><strong>'{"numeParam":"valoareParam"}'</strong>
  */
 function GenerateJSONString(arNumeParam,arValParam)
{
    var theString='';
    var theObject={};
    
    for (var i=0; i < arNumeParam.length;i++)
        {
          
          theObject[arNumeParam[i]]=arValParam[i];
        }
        theString=JSON.stringify(theObject);
//        alert(theString);
     return theString;   
}
function JSONSafeParse(text,returnText)
{
    try{
        return JSON.parse(text);
    }
    catch(er)
    {
        console.log("JSON parse error\n\r"+er);
        if (returnText)
            return text;
        else
            return null;
    }
}

/**
 * 
 * Arata un gif de incarcare dupa care apeleaza functia callBackFunction
 * (dimensiunea gif-ului este luata in functie de dimensiunea parintelui).
 * 
 * Folosita in general pentru IncarcaPaginaPHP, fiind apelata inainte de incarca pagina cu Ajax,
 * dupa care continutul Container-ului/Parintelui e inlocuit cu ce returneaza Ajax-ul
 * 
 * @param {String} Container id-ul parintelui in care sa apara loader-ul
 * @param {function} callBackFunction functia pe care o apeleaza dupa ce arata gif-ul 
 * @returns {undefined}
 */
function ShowLoader(Container,callBackFunction)
{
    if (typeof(Container)==="string")
        var theContainer=$('#'+Container);
    else
        var theContainer=Container;
    if (!Boolean(theContainer)) 
        {
            var callbacks = $.Callbacks();
            callbacks.add(callBackFunction);
            callbacks.fire();
            return false;
        }
    if (!Boolean(theContainer.position()))
    {
        var callbacks = $.Callbacks();
        callbacks.add(callBackFunction);
        callbacks.fire();
        return false;
    }
    var idLoaderDiv=AvailableID('div-dinam-loader');
    var idLoaderImg=AvailableID('img-dinam-loader');
    var divString='<div class="dinam-loader" id="'+idLoaderDiv+'"></div>';
    var gifName='loader';
    if ((theContainer.innerHeight()/2)>=64)
        gifName+='(64).gif';
    else
       if ((theContainer.innerHeight()/2)>32)
        gifName+='(32).gif'; 
       else
        gifName+='(24).gif';        
    var imgString='<img src="./library/img/'+gifName+'" class="dinam-img-loader" id="'+idLoaderImg+'">';
    theContainer.append(divString);
    var theDiv=$("#"+idLoaderDiv);
    theDiv.append(imgString);
    theDiv.css({
        top:(theContainer.position().top+parseInt(theContainer.css("marginTop")))+'px',
        left:(theContainer.position().left+parseInt(theContainer.css("marginLeft")))+'px',
        height:theContainer.outerHeight(),
        width:theContainer.outerWidth()
    });
    var callbacks = $.Callbacks();
    callbacks.add(callBackFunction);
    callbacks.fire();
    //$('#'+idContainer).removeChild($("#"+idLoaderDiv));
    return true;
}
function ClearLoader(parent)
{
    if (typeof(parent)==="undefined")
     {
        $(".dinam-loader").remove();
     }
   else
       {
           if (typeof(parent)==="string")
               parent=$("#"+parent);
           parent.children(".dinam-loader").remove();
       }
}

/**
 * 
 * Verifica daca elementul dat ca parametru <b>exista</b> sau nu.
 * 
 * @param {Object / String} paramElem id-ul elementului ca <b>String</b> 
 *                                    sau <br>chiar obiectul ca <b>Object</b> 
 * @returns {Boolean}
 */
function ElementExits(paramElem)
{
    var theElem;
    if (typeof(paramElem)==="string")
        theElem=$("#"+paramElem);
    else
        theElem=paramElem;
    //aici se poate modifica aceasta conditie pt a verifica daca un element exista
    // asta in caz ca aceasta nu merge bine :)
    if (theElem.length!==0)
        return true;
    else
        return false;
}
/**
 * 
 * Verifica daca elementul dat ca parametru este <b>vizibil</b> sau nu.
 * 
 * @param {Object / String} paramElem id-ul elementului ca <b>String</b> 
 *                                    sau <br>chiar obiectul ca <b>Object</b> 
 * @returns {Boolean}
 */
function ElementVisible(paramElem)
{
    var theElem;
    if (typeof(paramElem)==="string")
        theElem=$("#"+paramElem);
    else
        theElem=paramElem;
    //aici se poate modifica aceasta conditie pt a verifica daca un element exista
    // asta in caz ca aceasta nu merge bine :)
    if (theElem.is(":visible") || (theElem.css("display")!="none") )
        return true;
    else
        return false;
}
/**
 * 
 * Verifica pana cand elementul dat ca parametru este <b>vizibil</b> si abia 
 * apoi ruleaza functia data. 
 * 
 * @param {String / Object} theElem     id-ul elementului ca <b>String</b> 
 *                                      sau <br>chiar obiectul ca <b>Object</b> 
 * @param {function(theObj)} callback   functia pe care sa o execute dupa ce elementul e vizibil
 *                                      (theObj- elementul transmis ca parametru initial)
 * @param {Number} intervalOfChecking   timpul intre verificari <br> 
 *                                      <b>default:</b> 100
 * @param {Number} limitRuns            nr limita de verificari dupa care sa se opreasca<br> 
 *                                      <b>default:</b> 1000                                    
 */
function RunAfterElementVisible(theElem,callback,intervalOfChecking,limitRuns)
{
    if (typeof(limitRuns)==="undefined")
        limitRuns=500;
    if (typeof(intervalOfChecking)==="undefined")
        intervalOfChecking=100;
    try 
    {
    var myVar=
    setInterval(function DinamRun(){
            Console("RunAfterElementVisible...");
                 if (!DinamRun.counter)
                    DinamRun.counter=0;
                DinamRun.counter++;
                Console(DinamRun.counter);
                if ( DinamRun.counter>=limitRuns)
                   {
                       clearInterval(myVar);
                       DinamRun.counter=0;
                       Console("A depasit limita de incercari!"); 
                       return ;
                   }
                if (ElementVisible(theElem))
                    {
                        var callbacks = $.Callbacks();
                        callbacks.add(callback);
                        try
                        {
                            if (typeof(theElem)==="string")
                               callbacks.fire($("#"+theElem));
                            else
                               callbacks.fire(theElem);
                             
                        }
                       catch (err)
                       {
                           clearInterval(myVar);
                            Console("Eroare!");
                            DinamRun.counter=0;
                            Console(err.message);
                       }
                        clearInterval(myVar);
                        DinamRun.counter=0;
                        Console("Gata!");
                        return;
                    }
                    
            },
        intervalOfChecking);
        RunningFunction[RunningFunction.length]=myVar;
    }
    catch (err)
    {
       clearInterval(myVar);
       DinamRun.counter=0;
       Console("Eroare!");
       Console(err);
    }
}
/**
 * 
 * Verifica pana cand elementul dat ca parametru <b>exista</b> si abia 
 * apoi ruleaza functia data. 
 * 
 * @param {String / Object} theElem     id-ul elementului ca <b>String</b> 
 *                                      sau <br>chiar obiectul ca <b>Object</b> 
 * @param {function(theObj)} callback   functia pe care sa o execute dupa ce elementul exista
 *                                      (theObj- elementul transmis ca parametru initial)
 * @param {Number} intervalOfChecking   timpul intre verificari <br> 
 *                                      <b>default:</b> 100
 * @param {Number} limitRuns            nr limita de verificari dupa care sa se opreasca<br> 
 *                                      <b>default:</b> 1000  
 */
function RunAfterElementExits(theElem,callback,intervalOfChecking,limitRuns)
{
     if (typeof(limitRuns)==="undefined")
        limitRuns=500;
    if (typeof(intervalOfChecking)==="undefined")
        intervalOfChecking=100;
     try 
    {
        
    var myVar=
    setInterval(function DinamRun(){
        Console("RunAfterElementExits...");
                if (!DinamRun.counter)
                    DinamRun.counter=0;
                DinamRun.counter++;
//                console.log(DinamRun.counter);
                if ( DinamRun.counter>=limitRuns)
                   {
                       clearInterval(myVar);
                       DinamRun.counter=0;
                       Console("A depasit limita de incercari!"); 
                       return ;
                   }
                if (ElementExits(theElem))
                    {
                        var callbacks = $.Callbacks();
                        callbacks.add(callback);
                         try 
                        {
                            if (typeof(theElem)==="string")
                               callbacks.fire($("#"+theElem));
                            else
                               callbacks.fire(theElem);
                         }
                       catch (err)
                       {
                           clearInterval(myVar);
                           DinamRun.counter=0;
                            Console("Eroare!");
                            Console(err.message);
                       }
                        clearInterval(myVar);
                        DinamRun.counter=0;
                        Console("Gata!");
                        return;
                    }
                   
            },
        intervalOfChecking);
  }
                       catch (err)
                       {
                           clearInterval(myVar);
                           DinamRun.counter=0;
                            Console("Eroare!");
                            Console(err.message);
                       }
}

/**
 * Seteaza vizibile/ascunse elementele date in vectorul de id-uri
 * 
 * @param {String[]} arIDuri -[id1, id2, id3,...]
 * @param {String[]} arBool *[abool1, abool2,abool3,...];
 *                      *[abool] ->pt toate;
 *                      *[abool, aboo2] ->daca sunt mai multe id-uri... 
 *                      pt restul il considera pe ultimul;
 * @returns {undefined}
 */
function SetVisible(arIDuri,arBool)
{
    arBool=arBool||[true];
    var toateLaFel=(arBool.length===1);
    for (var i=0;i<arIDuri.length;i++)
        {
            if (toateLaFel)
                {
                    if (arBool[0]===true)
                        $("#"+arIDuri[i]).show();
                    else
                      $("#"+arIDuri[i]).hide();  
                }
            else
                if (i<arBool.length)
                    {
                      if (arBool[i]===true)
                        $("#"+arIDuri[i]).show();
                    else
                      $("#"+arIDuri[i]).hide();    
                    }
                else
                    {
                       if (arBool[arBool.length-1]===true)
                        $("#"+arIDuri[i]).show();
                    else
                      $("#"+arIDuri[i]).hide();  
                    }
        }
}

function randomString(length, chars) {
    if (length<=0)
        return "";
    if (typeof(chars)==="undefined")
        chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var result = '';
    for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
    return result;
}
/**
 * Verifica daca este browser de mobil
 * @returns {bool} true if mobile
 */
function IsMobile()
{
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|SymbianOS|Symbian|SymbOS/i.test(navigator.userAgent);  
}
/**
 * Fields:
 * Android,BlackBerry,iOS,Opera,Windows, any;
 * Fields type: bool;
 * 
 * @type Object
 * 
 */
var isAMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i)||navigator.userAgent.match(/Opera Mobi/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    Symbian: function() {
        return navigator.userAgent.match(/SymbianOS/i)|| navigator.userAgent.match(/Symbian/i)
        || navigator.userAgent.match(/SymbOS/i);
    },
    any: function() {
        return (isAMobile.Android() || isAMobile.BlackBerry() || isAMobile.iOS() || isAMobile.Opera() || isAMobile.Windows());
    }
};
/**
 * 
 * @param {Float / String} x
 * @returns {String} numarul cu "," pt mie si "." pt zecimale
 */
function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

/**
 * 
 * Pune clasa "active" elementului si o elimina de la fratii lui.
 * 
 * @param {String/Object} elem
 * @param {bool} aBool
 *  
 * */

 function SetActiveElement(elem,aBool)
{
    var theElem;
    if (typeof(elem)==='string')
        {  
          theElem=$("#"+elem);  
        }
    else
        theElem=elem;
   if (aBool===undefined) aBool=true;
   theElem.siblings(".active").removeClass("active");
  //$("."+$('li#'+elemId).attr('class')).removeClass("active");
  if (aBool===true)
    theElem.addClass("active");
  else
    theElem.removeClass("active");
}

/**
 * 
 * Creeaza un mesaj de alerta in parintele dat.
 * 
 * @param {String} titlu 
 * @param {String} mesaj
 * @param {String} tip ""/"success"/"warning"/"danger"/"info"
 * @param {String} idParinte  Daca parintele nu e dat suprascrie TOT documentul html 
 * @param {bool} supraScrieParinte
 * @param {Number} timeUntilClose Daca timpul este dat se inchide dupa "timeUntilClose" milisecunde...
 * @@param {bool} prepend        Daca pune mesajul inaintea continutului parintelui 
 */
function ArataMesajAlerta(titlu,mesaj,tip,idParinte,supraScrieParinte,timeUntilClose,prepend)
{
    
    var idAlerta="divAlerta-in";
    idAlerta=AvailableID(idAlerta);
    if (typeof(prepend)==="undefined")
        prepend=false;
    //daca parintele nu e dat suprascrie TOT documentul html 
    if (idParinte!==undefined && idParinte!=="")
        {
            //alert('t: '+titlu+' m: '+mesaj+' t: '+tip+' i: '+idParinte);
        if (supraScrieParinte!==undefined)
            if (supraScrieParinte===true)
                $("#"+idParinte).empty();
        if (prepend)
            {
               $("#"+idParinte).prepend("<div id='"+idAlerta+"' class='alert fade in'>\n\
        <button type='button' class='close' id='"+idAlerta+"-close'>&times;</button>\n\
        </div>"); 
            }
        else
            {
               $("#"+idParinte).append("<div id='"+idAlerta+"' class='alert fade in'>\n\
        <button type='button' class='close' id='"+idAlerta+"-close'>&times;</button>\n\
        </div>"); 
            }
            
           
        }
    else
        {
            //!!!PROBLEME pt ajax cand parintele nu e dat---- TO DO
            if (IsMobile())
                {
                    //la mobil pun pe toata latimea ecranului
                   $("body").append("<div  style='position:fixed;top:5px;left:0px; width:100%;'>\n\
                <div id='"+idAlerta+"' class='alert fade in '>\n\
                <button type='button' class='close' id='"+idAlerta+"-close'>&times;</button>\n\
                </div></div>"); 
                }
            else
                {
                   $("body").append("<div  style='position:fixed;top:5px;left:30%; width:40%;min-width:100px;'>\n\
                        <div id='"+idAlerta+"' class='alert fade in my-shadow'>\n\
                <button type='button' class='close' id='"+idAlerta+"-close'>&times;</button>\n\
                </div></div>"); 
                }
            
        }
  // daca nu e dat ramane doar clasa alert (adica tot "warning"- alerta aia galbejita) 
   if (tip!==undefined && tip!=="")
     $("#"+idAlerta).addClass('alert-'+tip);
 //tiltul cu bold... dar se poate da orice html ca parametru
   if (titlu!==undefined  && titlu!=="")
     $("#"+idAlerta).append('<strong>'+titlu+' </strong>');
 //mesajul... ca orice html
   if (mesaj!==undefined && mesaj!=="")
     $("#"+idAlerta).append('<span>'+mesaj+'</span>');
 //daca nu... ramane vizibila cu "x" de inchidere... deci nu ar trebui sa streseze userul
 if (timeUntilClose!==undefined && timeUntilClose>0)
        setTimeout(function(){$("#"+idAlerta).alert('close');},timeUntilClose);
 //activez butonul de inchidere ("x"-uletul)
   $("#"+idAlerta+"-close").click(function(){
      $("#"+idAlerta).alert('close'); 
   });
   //in sfarsil afisez alarma!!!
   $("#"+idAlerta).alert();
}
  function MyFormatDate(aDate)
  {
      if (typeof(aDate)==="string")
          aDate=new Date(aDate);
    try
    {
        var day=aDate.getDate();
        var month=aDate.getMonth()+1;
        var year=aDate.getFullYear();
        if (day<10)
            day="0"+day;
        if (month<10)
            month="0"+month;
        return day+"."+month+"."+year;
    }
    catch(err)
    {
        console.error(err);
        return "";
    }
  }
  
  /**
* 
*   Returneaza id-ul disponibil adaugand un nr la numele dat ca parametru 
*   pana gaseste unul care nu e luat.
   Utila cand creez elemente dinamic... 
 * @param {String} theID
 * @returns {String} id-ul disponibil
 *  */
function AvailableID(theID)
{
    if (!($('#'+theID)[0]))
        return theID;
   var i=0;
    while ($('#'+theID+i)[0])
        {
            i++;
        } 
    return theID+''+i;
}

/** 
 * Creeaza un buton cu o adresa de redirectare (de fapt un link cu forma de buton)
 * @param {String} returnTo         Adresa de redirectare
 * @param {String} idContainer      parintele in care sa creeze butonul 
 * @param {String} text             textul butonului
 * @param {String} icon             daca e dat pune o iconita ca sa fie cat mai sugestiv 
 *                                          (numele iconului trebuie dat fara "icon-" )
 * @param {String} HPos             unde sa puna butonul pe orizontala    : "left"/"right"/""
 * @param {String} VPos             unde sa puna butonul pe verticala     : "top"/"bottom"
 * @param {String} IconPos          unde sa fie iconita pozitionata       : "left"/"right"
 * @param {String} additionalClass  
 * @param {String} aditionalAttr    
 *  */
function InsertRedirectButton(returnTo,idContainer,text,icon,HPos,VPos,IconPos,additionalClass,aditionalAttr)
{
 icon=icon||'';   
 HPos=HPos||'';
 additionalClass=additionalClass||'';
 aditionalAttr=aditionalAttr||'';
 VPos=VPos||'top';
 IconPos=IconPos||'left';
 if (icon!=='')
     icon='icon-'+icon;
 if (HPos!=='')
     HPos='pull-'+HPos;
 var htmlComponenta='<div class=" '+HPos+' '+additionalClass+'" '+aditionalAttr+'>'+
                                    '<div class="btn-group  '+HPos+'">'+
                                         '<a href="'+returnTo+'" class="btn">';
                                 
    if (IconPos==='left')
        htmlComponenta+=                             
                                             '<i class="'+icon+'"></i>'+
                                             '<span> '+text+' </span>';
    else
       if (IconPos==='right')
            htmlComponenta+=                                       
                                             '<span> '+text+' </span>'+
                                             '<i class="'+icon+'"></i>';
       else
          htmlComponenta+=                   '<span> '+text+' </span>'; 
    htmlComponenta+=
                                         '</a>'+
                                    '</div>'+         
                                 '</div>';
//                         Console(htmlComponenta);
 if (VPos==='bottom')
    $("#"+idContainer).append(htmlComponenta);
 else
    if (VPos==='top')
        $("#"+idContainer).prepend(htmlComponenta);  
}

/**
 * Format dd.MM.yyyy
 * @type String Data formatata
 */
function GetCurentDate(){
    var currentTime = new Date();
var month = currentTime.getMonth() + 1;
if (month<10) 
    month='0'+month;
var day = currentTime.getDate();
if (day<10) 
    day='0'+day;
var year = currentTime.getFullYear();
var theString=day+'.'+month+'.'+year;
    //var d=new Date();
    return theString;
        } 
/**
 * 
 * Format hh:mm
 * 
 * @param {bool} roundToHalf Daca trebuie sa returneaze timpul aproximat la "si jumate" sau "la fix" 
 * @type String Timpul formatat
 */
function GetCurrentTime(roundToHalf){
    roundToHalf=roundToHalf||false;
    var currentTime= new Date();
    var hour=currentTime.getHours();
    var min=currentTime.getMinutes();
    var theString="";
    if (roundToHalf)
        {
            if ((min>0)&&(min<=15))
                min=0;
            else
              if ((min>15)&&(min<=45))
                min=30;
              else  
                  if ((min>45))
                      {
                        min=0;
                        hour++;
                      }
        }
        if (String(hour).length<2)
            hour="0"+hour;
        if (String(min).length<2)
            min="0"+min;
     theString+=hour+":"+min;   
     return theString;
}