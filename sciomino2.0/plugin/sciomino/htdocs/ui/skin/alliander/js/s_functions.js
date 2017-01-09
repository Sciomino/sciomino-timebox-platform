/* Variabelen bepaald door Alliander op de Wiki onder 'SiteCatalyst richtlijnen' */
// setups
s.currDateObj=new Date()
s.currYear=s.currDateObj.getFullYear()
s.dstStart="1/1/"+s.currYear;
s.dstEnd="31/12/"+s.currYear;
s.currentYear=s.currYear;

// plugins
s.usePlugins=true;

// s_doPlugins wordt aangeroepen bij t(), tl(), page exit, download
function s_doPlugins(s) {

    // standaard properties datum, tijd, herhaling
    s.prop2=(typeof(s.prop2) != "undefined" && s.prop2)?s.prop2:s.getTimeParting("h","+1");
    s.prop1=(typeof(s.prop1) != "undefined" && s.prop1)?s.prop1:s.getTimeParting("d","+1");
    s.prop3 = s.getNewRepeat('sc_newrepeat');

    // server
    s.server= window.location.host;

    // hierarchie
    s.hier1 = s.pageName;

    //dynamic page indentification
    var page = document.title;
    if(page.indexOf('403') >= 0 || page.indexOf('404')  >= 0) {
      s.pageType='errorPage';
    }

    // campagnes
    s.campaign = s.getQueryParam("cmp");
    s.prop6 = s.getQueryParam("icmp");
    s.prop7 = s.getQueryParam("sc_pkw");

    //Copy eVar
    s.eVar1 = s.prop1;
    s.eVar2 = s.prop2;
    s.eVar3 = s.prop3;
    s.eVar4 = s.prop4;
    s.eVar5 = s.prop5;
    s.eVar6 = s.pageName;
    s.eVar7 = s.prop7;
    s.eVar8 = s.prop8;
    s.eVar10 = s.prop10;
    s.eVar11 = s.prop11;
    s.eVar12 = s.prop12;

    // hier eventueel eigen extra code
}

s.doPlugins=s_doPlugins;

// code to make page name compatible for Sitecatalyst
function makeStringSitecatalystProof(strInput){
  var forbiddenCharactersRegExp="[!\"';,]";
  var re = new RegExp(forbiddenCharactersRegExp, "g");
  return strInput.replace(re, " ");
}

// stel pagina pad + naam samen voor Sitecatalyst:
// prefix + dir + htmltitle + form number
function getSitecatalystPageName(page_prefix) {
  var page_name = location.pathname;
  if (document.getElementById('pageindicator')) {
    page_name = page_name + '-' + document.getElementById('pageindicator').title;
  }
  if (page_prefix != null ) {
    page_name = page_prefix + page_name;
  }
  return makeStringSitecatalystProof(page_name);
}

/*
 * Plugin: getTimeParting 2.0 - Set timeparting values based on time zone 
 */
s.getTimeParting=new Function("t","z",""+"var s=this,cy;dc=new Date('1/1/2000');"+"if(dc.getDay()!=6||dc.getMonth()!=0){return'Data Not Available'}"+"else{;z=parseFloat(z);var dsts=new Date(s.dstStart);"+"var dste=new Date(s.dstEnd);fl=dste;cd=new Date();if(cd>dsts&&cd<fl)"+"{z=z+1}else{z=z};utc=cd.getTime()+(cd.getTimezoneOffset()*60000);"+"tz=new Date(utc + (3600000*z));thisy=tz.getFullYear();"+"var days=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday',"+"'Saturday'];if(thisy!=s.currentYear){return'Data Not Available'}else{;"+"thish=tz.getHours();thismin=tz.getMinutes();thisd=tz.getDay();"+"var dow=days[thisd];var ap='AM';var dt='Weekday';var mint='00';"+"if(thismin>30){mint='30'}if(thish>=12){ap='PM';thish=thish-12};"+"if (thish==0){thish=12};if(thisd==6||thisd==0){dt='Weekend'};"+"var timestring=thish+':'+mint+ap;if(t=='h'){return timestring}"+"if(t=='d'){return dow};if(t=='w'){return dt}}};");

/*
 * Plugin: getNewRepeat 1.2 - Returns whether user is new or repeat 
 */
s.getNewRepeat=new Function("d","cn",""+"var s=this,e=new Date(),cval,sval,ct=e.getTime();d=d?d:30;cn=cn?cn:"+"'s_nr';e.setTime(ct+d*24*60*60*1000);cval=s.c_r(cn);if(cval.length="+"=0){s.c_w(cn,ct+'-New',e);return'New';}sval=s.split(cval,'-');if(ct"+"-sval[0]<30*60*1000&&sval[1]=='New'){s.c_w(cn,ct+'-New',e);return'N"+"ew';}else{s.c_w(cn,ct+'-Repeat',e);return'Repeat';}");
/*
 * Utility Function: split v1.5 (JS 1.0 compatible)
 */
s.split=new Function("l","d",""+"var i,x=0,a=new Array;while(l){i=l.indexOf(d);i=i>-1?i:l.length;a[x"+"++]=l.substring(0,i);l=l.substring(i+d.length);}return a");

/*
 * Plugin: getQueryParam 2.3
 */
s.getQueryParam=new Function("p","d","u",""
+"var s=this,v='',i,t;d=d?d:'';u=u?u:(s.pageURL?s.pageURL:s.wd.locati"
+"on);if(u=='f')u=s.gtfs().location;while(p){i=p.indexOf(',');i=i<0?p"
+".length:i;t=s.p_gpv(p.substring(0,i),u+'');if(t){t=t.indexOf('#')>-"
+"1?t.substring(0,t.indexOf('#')):t;}if(t)v+=v?d+t:t;p=p.substring(i="
+"=p.length?i:i+1)}return v");
s.p_gpv=new Function("k","u",""
+"var s=this,v='',i=u.indexOf('?'),q;if(k&&i>-1){q=u.substring(i+1);v"
+"=s.pt(q,'&','p_gvf',k)}return v");
s.p_gvf=new Function("t","k",""
+"if(t){var s=this,i=t.indexOf('='),p=i<0?t:t.substring(0,i),v=i<0?'T"
+"rue':t.substring(i+1);if(p.toLowerCase()==k.toLowerCase())return s."
+"epa(v)}return ''");

