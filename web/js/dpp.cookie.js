/*
* for Dynamic price processor provided by PMEWEB
* generate UUID for cookie
*/
var jd = jQuery.noConflict();
var dppCookieName = "pmeweb.dpp.client";
//var dppHttpAdr ="http://www.tigertrigger.fr/dynaprice/web/ajax/";
var dppHttpAdr ="http://192.168.0.12/dynaprice/web/ajax/";
var dppCliDomaine = "prestashop.com";
if (jd.cookie(dppCookieName) == null) {
    var dppUid = uuid.v4();
    jd.cookie(dppCookieName,dppUid,{ expires: 7 });
} else {
       var dppUid = jd.cookie(dppCookieName);
}



