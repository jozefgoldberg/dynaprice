/*
* for Dynamic price processor provided by PMEWEB
* generate UUID for cookie
*/
var dppCookieName = "pmeweb.dpp";
var httpAdr ="http://localhost/dynaprice/web/app_dev.php/buyers/add/";
if ($.cookie(dppCookieName) == null) {
    var dppUid = uuid.v4();
    $.cookie(dppCookieName,dppUid,{ expires: 7 });
} else {
       var dppUid = $.cookie(dppCookieName);
}

