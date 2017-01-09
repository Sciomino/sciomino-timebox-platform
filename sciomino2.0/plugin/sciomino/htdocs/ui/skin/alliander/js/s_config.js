/* SiteCatalyst code version: H.25.
Copyright 1996-2012 Adobe, Inc. All Rights Reserved
More info available at http://www.omniture.com */

var s_account=SiteCatalyst_GetAccountFromHostname(window.location.hostname)
var s=s_gi(s_account)
/************************** CONFIG SECTION **************************/
/* You may add or alter any code config here. */
s.charSet="ISO-8859-1"
/* Conversion Config */
s.currencyCode="EUR"
/* Link Tracking Config */
s.trackDownloadLinks=true
s.trackExternalLinks=true
s.trackInlineStats=true
s.linkDownloadFileTypes="exe,zip,wav,mp3,mov,mpg,avi,wmv,pdf,doc,docx,xls,xlsx,ppt,pptx"
s.linkInternalFilters="javascript:,www.alliander.com, www.liander.nl, www.liandon.nl, telefoonboek2.alliander.local"
s.linkLeaveQueryString=false
s.linkTrackVars="None"
s.linkTrackEvents="None"

/* WARNING: Changing any of the below variables will cause drastic
changes to how your visitor data is collected.  Changes should only be
made when instructed to do so by your account manager.*/
s.visitorNamespace="alliander"
s.trackingServer="stats.alliander.com"

/**
 * Bepaald het Account (s_account) voor SiteCatalyst vanuit een hostname
 * Patroon: advalliander<domein><dev|prod>
 */
function SiteCatalyst_GetAccountFromHostname(hostname) {
	var localPattern = /.local$/;
	var subdomainPattern = /^([\w-]+)\./;
	var domainPattern = /\w+\.(\w+)\.\w+$/;
	var domain = "";
	var env = "";

	// Als de hostname een lokale hostname is wordt het s_account anders bepaald
	if (localPattern.test(hostname)) {
		if ( subdomainPattern.test(hostname) ) {
			// Environment en Domain zitten beide in het subdomein
			domain = SiteCatalyst_GetDomainFromSubdomain(subdomainPattern.exec(hostname)[1])
			env = SiteCatalyst_GetEnvironment(subdomainPattern.exec(hostname)[1]);
		}
	}
	else {
		if ( domainPattern.test(hostname) ) { domain = domainPattern.exec(hostname)[1]; }
		if ( subdomainPattern.test(hostname) ) { env = SiteCatalyst_GetEnvironment(subdomainPattern.exec(hostname)[1]); }
	}
	
	return 'advalliander' + domain + env;
}

/**
 * Bepaald of een subdomein een DEV of PROD subdomein is volgens de afgesproken subdomein standaarden
 * Bijvoorbeeld: acc-www.alliander.com (DEV), dev-geodiensten.alliander.local (DEV), www.liander.nl (PROD)
 */
function SiteCatalyst_GetEnvironment(subdomain) {
	var envPattern = /[dev|acc|test]\-/;
	if (envPattern.test(subdomain)) { return "dev"; } else { return "prod"; }
}

/**
 * Voor een lokale hostname wordt het domein bepaald in het subdomein
 * Bijvoorbeeld: dev-geodiensten.alliander.local (geodiensten), wiki.alliander.local (wiki)
 */
function SiteCatalyst_GetDomainFromSubdomain(subdomain) {
	var domainPattern = /-?([\w]+)$/;
	if (domainPattern.test(subdomain)) { return domainPattern.exec(subdomain)[1]; } else { return null; }
}