if ('serviceWorker' in navigator) {
	window.addEventListener('load', function () {
		navigator.serviceWorker.register('/sw.js').then(function (registration) {
			// Registration was successful
			console.log('ServiceWorker registration successful with scope: ', registration.scope);
		}, function (err) {
			// registration failed :(
			console.log('ServiceWorker registration failed: ', err);
		});
	});
}

window.addEventListener('beforeinstallprompt', function (e) {
	e.prompt();
});

window.addEventListener('appinstalled', () => {
	console.log('a2hs installed');
});


// Detects if device is on iOS
// const isIos = () => {
// 	const userAgent = window.navigator.userAgent.toLowerCase();
// 	return /iphone|ipad|ipod/.test(userAgent);
// };
// Detects if device is in standalone mode
// const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

// Checks if should display install popup notification:
// if (false && isIos() && !isInStandaloneMode()) {
// 	(function ($) {
// 		if ($('#ios-add-to-homescreen').length) {
// 			$('#ios-add-to-homescreen').show();
//
// 			$('.js-close-a2h').on('click', function () {
// 				$('#ios-add-to-homescreen').remove();
// 			});
// 		}
// 	})(jQuery);
// }


