/*
* for Dynamic price processor provided by PMEWEB
* generate UUID for cookie
*/
var dppCookieName = "pmeweb.dpp.client";
var dppHttpAdr ="http://192.168.0.12/dynaprice/web/ajax/";
var dppCliDomaine = "oneclient.com";
if ($.cookie(dppCookieName) == null) {
    var dppUid = uuid.v4();
    $.cookie(dppCookieName,dppUid,{ expires: 7 });
} else {
       var dppUid = $.cookie(dppCookieName);
}



