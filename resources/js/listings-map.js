import geolocation from './geolocation';

geolocation.get().then(function (r) {
	console.log(r);
});

geolocation.watch(function(r){
	
	console.log(r);
});
