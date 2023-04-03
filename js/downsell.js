function addSrc() {
					 // Início da config
					 var keyOnCurrentUrl = "_o";
					 var newKeyOnLinks = "_o";
					 var links = window.document.querySelectorAll("a.downsell");
					 var previousSrc = window.location.search.match(/_o=(.*?)(:?\&|#|$)/);
					 var sourceValue = !previousSrc ? "" : previousSrc[1]; 
					 for (var i in links) {
						 if (typeof links[i].href !== "string") return;
						 links[i].href = links[i].href.replace(
							 /(^.*?)(?:\?(.*?))?(#.*|$)/,
							 function (match, urlAndPath, originalQueryString, anchor) {
								 var newQueryString = [];
								 var keyExistsOnCurrentUrl = false;
								 if (typeof originalQueryString === "string") {
									 newQueryString = originalQueryString.split("&").reduce(function (queryString, queryPart) {
										 if (!queryPart.startsWith(keyOnCurrentUrl + "="))
											 queryString.push(queryPart)
										 else {
											 keyExistsOnCurrentUrl = true;
											 var data = queryPart.split("=")
											 queryString.push(newKeyOnLinks + "=" + sourceValue + "_" + data[1]);
										 }
										 return queryString;
									 }, []);
								 }
								 if (!keyExistsOnCurrentUrl) {
									 newQueryString.push(newKeyOnLinks + "=" + sourceValue);
								 }
								 return urlAndPath + "?" + newQueryString.join("&") + anchor;
							 }
						 );
					 }
				 }
		   window.addEventListener("load", addSrc);