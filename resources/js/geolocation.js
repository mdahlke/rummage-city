class GeoLocation {
    constructor() {
        console.log('instantiated');
    }

    requestPermission() {

    }

    get() {
        return new Promise(function (resolve, reject) {
            navigator.geolocation.getCurrentPosition(function (r) {
                resolve(r);
            }, reject);
        });
    }

    poll() {

    }

    watch(success, err = function () {
    }) {
        navigator.geolocation.watchPosition(success, err);
    }
}

const g = new GeoLocation();

export default g;
