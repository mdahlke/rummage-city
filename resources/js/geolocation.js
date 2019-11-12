class GeoLocation {
	constructor() {

	}

	requestPermission() {
	}

	get() {
		return new Promise(function (resolve, reject) {

			navigator.geolocation.getCurrentPosition(function (r) {
				resolve(r);
			});
		});
	}

	poll(){

	}

	watch(success, err = function(){}) {
		navigator.geolocation.watchPosition(success, err);
	}
};

const g = new GeoLocation();
g.requestPermission();

export default g;
