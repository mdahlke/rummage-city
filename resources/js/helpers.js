/**
 * Add JavaScript files to the <head>
 * @param urls
 */
export const script2Head = function (urls) {
	let script;
	
	if (urls.constructor !== Array) {
		urls = [urls];
	}
	
	for (var i = 0; i < urls.length; i++) {
		script = document.createElement('script');
		script.setAttribute('src', urls[i]);
		document.getElementsByTagName('head')[0].appendChild(script);
	}
};


export const empty = function (subject) {
	return (typeof subject === 'undefined' || subject === '' || subject === null || subject === false);
};
