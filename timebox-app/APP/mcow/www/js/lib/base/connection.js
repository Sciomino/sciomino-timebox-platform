// Connection with external system

MCOW.Connection = {
	
	// http://stackoverflow.com/questions/10361699/wait-for-json-call-to-be-completed-in-javascript
	getResponse : function(url, callback) {

		if (MCOW.Config["debug_connection"] == '1') {console.log("Connection, getResponse: url = " + url);}

		// read cache
		var cache = {};
		if (MCOW.Config["connection_cache"] == '1') {
			// check cache
			// - experation in miliseconds
			var expiration = 100000;
			cache = MCOW.Connection.readCache(expiration, url);
		}
		// use if available
		if (MCOW.Config["connection_cache"] == '1' && cache['status'] == 1) {
			var data = JSON.parse(cache['content']);

			if (MCOW.Config["debug_connection"] == '1') {console.log("Connection, getResponse: data = " + JSON.stringify(data));}
			callback(data);
		}
		// connect
		else {
			MCOW.Lib.Data2.loadFileWithCallback(url, function(httpRequest) {
				/*
				switch (httpRequest.readyState) {
				case 1:
					alert("The request is opened, but the send method has not been called yet.<br />");
					break;
				case 2:
					alert("The request is sent but no data has been received yet.<br />");
					break;
				case 3:
					alert("A part of the data has been received.<br />");
					break;
				case 4:
					alert("Done.<br />");
					break;
				};
				*/
				if ((httpRequest.readyState === 4) && (httpRequest.status === 200)) {
					// alert("ok: " + httpRequest.responseText);
					var json = httpRequest.responseText;
					// http://stackoverflow.com/questions/3710204/how-to-check-if-a-string-is-a-valid-json-string-in-javascript-without-using-try
					try {
						var data = JSON.parse(json);

						if (data && typeof data === "object" && data !== null) {
							// write cache
							if (MCOW.Config["connection_cache"] == '1') {
								MCOW.Connection.writeCache(url, json);
							} 

							if (MCOW.Config["debug_connection"] == '1') {console.log("Connection, getResponse: data = " + JSON.stringify(data));}
							callback(data);
						}
					}
					catch (e) { 
						//alert("not json");
						callback(JSON.parse('{"error" : "data content is not JSON"}'));
					}				
				}
				else {
					// if not status OK
					if (httpRequest.readyState === 4) {
						//alert("not ok: " + httpRequest.responseText);
						callback(JSON.parse('{"error" : "data status is not 200 OK"}'));
					}
				}			
				});
		}
	},
	
	/*
	* CACHE
	* - Check for number of chache entries/needs garbage collect to remove old entries
	* - do not renew the cache if the network is down.
	*/
	readCache : function(expiration, url) {

		//alert("get");
		var cache = {};
		cache['status'] = 0;
		cache['content'] = "";

		var entry = localStorage.getItem('MCOW.CACHE.'+url);
		entry = JSON.parse(entry);
		if (entry) {
			if (entry['timestamp'] > (new Date().getTime() - expiration)) {
				cache['status'] = 1;
				cache['content'] = atob(entry['content']);
			}
			else {
				// remove old entry
				MCOW.Connection.deleteCache(url);
			}
		}

		MCOW.Connection.collectGarbageFromCache(expiration);
		
		return (cache);

	},

	writeCache : function(url, content) {

		//alert("set");
		var timestamp = new Date().getTime();

		localStorage.setItem('MCOW.CACHE.'+url, '{"timestamp":"'+timestamp+'","content":"'+btoa(content)+'"}');

		return 1;

	},

	deleteCache : function(url) {

		//alert("remove");
		localStorage.removeItem('MCOW.CACHE.'+url);
		
		return 1;

	},

	deleteCacheByKey : function(key) {

		//alert("remove by key");
		localStorage.removeItem(key);
		
		return 1;

	},
	
	collectGarbageFromCache : function(expiration) {
		var entry = "";
		for (i=0; key = localStorage.key(i); i++) {
			if (key.lastIndexOf('MCOW.CACHE.', 0) === 0) {
				entry = localStorage.getItem(key);
				entry = JSON.parse(entry);
				if (entry) {
					if (entry['timestamp'] < (new Date().getTime() - expiration)) {
						// remove old entry
						MCOW.Connection.deleteCacheByKey(key);
					}
				}
			}
		}

	}

	
}
